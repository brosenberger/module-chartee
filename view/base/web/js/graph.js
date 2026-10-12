

define([
    "jquery",
    'graph-chartjs',
    'graph-chartjs-boxplot',
    'graph-chartjs-stacked100',
    'graph-chartjs-autocolors',
    'graph-chartjs-geo'
], function(
    $,
    Chart4,
    ChartBoxplot,
    ChartStacked100,
    ChartAutocolors,
    ChartGeo
) {
    "use strict";

    // unlike the other chart plugins, chartjs-chart-geo does not register itself
    Chart4.register(ChartGeo.ChoroplethController, ChartGeo.GeoFeature, ChartGeo.ColorScale, ChartGeo.ProjectionScale);

    $.widget('custom.graph', {
        options: {
            type: "line",
            data: {},
            options: {},
            plugins: [],
            topojson: null
        },

        _create: function() {
            this._resolveGeoFeatures();
            this.chart = new Chart4(this.element, {
                "type": this.options.type,
                "data": this.options.data,
                "options": this.options.options,
                "plugins": this.preparePluginList(this.options.plugins, this.options.options)
            });

            this.element.on('click', function(e){
                console.log("You click on element: " + e.target);
            });

        },
        /**
         * Replaces the country references (`feature`: name or id) of geo datasets by the GeoJSON features of the
         * configured topology ({topology: {...}, object: 'countries'}); unknown references are dropped and the labels
         * are taken from the feature names of the first dataset.
         */
        _resolveGeoFeatures: function() {
            var geo = this.options.topojson;
            if (!geo) {
                return;
            }
            var features = {};
            ChartGeo.topojson.feature(geo.topology, geo.topology.objects[geo.object]).features.forEach(function(feature) {
                features[feature.properties.name] = feature;
                if (feature.id !== undefined) {
                    features[feature.id] = feature;
                }
            });
            this.options.data.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.filter(function(item) {
                    item.feature = features[item.feature];
                    return item.feature !== undefined;
                });
            });
            if (this.options.data.datasets.length) {
                this.options.data.labels = this.options.data.datasets[0].data.map(function(item) {
                    return item.feature.properties.name;
                });
            }
        },
        /**
         * Show only datasets whose property `key` is contained in `values`; null shows all.
         */
        filterDataSets: function(key, values) {
            var chart = this.chart;
            chart.data.datasets.forEach(function(dataset, index) {
                chart.setDatasetVisibility(index, values === null || values.indexOf(String(dataset[key])) !== -1);
            });
            chart.update();
        },
        preparePluginList: function(plugins, options) {
            var that = this;
            var newPluginsObject = [];
            plugins.forEach(function(plugin, index) {
                if (typeof that['_' + plugin + 'Plugin'] === 'function')
                    newPluginsObject.push(that['_' + plugin + 'Plugin'](options));
                else
                    newPluginsObject.push(plugin);
            });
            return newPluginsObject;
        },
        _autocolorsPlugin: function(options) {
          return ChartAutocolors;
        },
        _stacked100Plugin: function(options) {
            return ChartStacked100.default;
        },
        _gaugeNeedlePlugin: function(options) {
            return {
                id: 'gaugeNeedle',
                afterDatasetDraw(chart, args, options) {
                    const { ctx, config, data, chartArea: { top, bottom, left, right, width, height } } = chart;

                    ctx.save();
                    const dataTotal = data.datasets[0].sumValue;
                    const needleValue = data.datasets[0].needleValue;
                    const angle = Math.PI + (1 / dataTotal * needleValue) * Math.PI;

                    var cx = 0;
                    var cy = 0;

                    if (chart.getDatasetMeta(0).data[2] !== undefined) {
                        cx = chart.getDatasetMeta(0).data[0].x + (chart.getDatasetMeta(0).data[2].x -chart.getDatasetMeta(0).data[0].x) / 2;
                        cy = chart.getDatasetMeta(0).data[0].y;
                    }

                    ctx.save();

                    // needle
                    ctx.translate(cx, cy);
                    ctx.rotate(angle);
                    ctx.beginPath();
                    ctx.moveTo(0, -2);
                    ctx.lineTo(chart.getDatasetMeta(0).data[0].innerRadius + 10, 0);
                    ctx.lineTo(0, 2);
                    ctx.fillStyle = '#444';
                    ctx.fill();
                    ctx.restore();

                    // needle dot
                    ctx.beginPath();
                    ctx.arc(cx, cy, 5, 0, 10);
                    ctx.fill();
                    ctx.restore();


                    ctx.font = '50px Arial';
                    ctx.fillStyle = '#444';
                    ctx.fillText(needleValue, cx, cy +50);
                    ctx.textAlign = 'center';
                    ctx.restore();

                }
            }
        },
        _doughnutLabelsLinePlugin: function(options) {
            return {
                id: 'doughnutLabelsLine',
                afterDraw(chart, args, options) {
                    const {ctx, chartArea: { top, bottom, left, right, width, height}} = chart;

                    chart.data.datasets.forEach((dataset, i) => {
                        chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                            const {x, y} = datapoint.tooltipPosition();
                            const color = Array.isArray(dataset.borderColor)
                                ? dataset.borderColor[index]
                                : (dataset.borderColor || '#444');

                            // draw line
                            const halfHeight = height / 2;
                            const halfWidth = width / 2;

                            const lineLength = (datapoint.outerRadius - datapoint.innerRadius) / 2;

                            const xLine = x >= halfWidth ? x + lineLength : x - lineLength;
                            const yLine = y >= halfHeight ? y + lineLength : y - lineLength;
                            const extraLine = x >= halfWidth ? lineLength : -lineLength;

                            ctx.beginPath();
                            ctx.moveTo(x, y);
                            ctx.lineTo(xLine, yLine);
                            ctx.lineTo(xLine + extraLine, yLine);
                            ctx.strokeStyle = color;
                            ctx.stroke();

                            // draw label
                            const textWidth = ctx.measureText(chart.data.labels[index]).width;
                            ctx.font = '12px Arial';

                            // control the position
                            const textXPosition = x >= halfWidth ? 'left' : 'right';
                            const plusFivePx = x >= halfWidth ? 5 : -5;

                            ctx.textAlign = textXPosition;
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = color;
                            ctx.fillText(chart.data.labels[index], xLine + extraLine + plusFivePx, yLine);
                        });
                    });
                }
            }
        }
    });

    return $.custom.graph;
});
