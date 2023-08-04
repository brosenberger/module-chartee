

define([
    "jquery",
    'graph-chartjs',
    'graph-chartjs-boxplot'
], function(
    $,
    Chart4
) {
    "use strict";

    $.widget('custom.graph', {
        options: {
            type: "line",
            data: {},
            options: {},
            plugins: []
        },

        _create: function() {
            self = this;

            var chart = new Chart4(this.element, {
                "type": this.options.type,
                "data": this.options.data,
                "options": this.options.options,
                "plugins": this.preparePluginList(this.options.plugins, this.options.options)
            });

            this.element.on('click', function(e){
                console.log("You click on element: " + e.target);
            });

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
        _doughnutLablesLinePlugin: function(options) {
            return {
                id: 'doughnutLablesLine',
                afterDraw(chart, args, options) {
                    const {ctx, chartArea: { top, bottom, left, right, width, height}} = chart;

                    chart.data.datasets.forEach((dataset, i) => {
                        chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                            const {x, y} = datapoint.tooltipPosition();

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
                            ctx.strokeStyle = dataset.borderColor[index];
                            ctx.stroke();

                            // draw label
                            const textWidth = ctx.measureText(chart.data.labels[index]).width;
                            ctx.font = '12px Arial';

                            // control the position
                            const textXPosition = x >= halfWidth ? 'left' : 'right';
                            const plusFivePx = x >= halfWidth ? 5 : -5;

                            ctx.textAlign = textXPosition;
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = dataset.borderColor[index];
                            ctx.fillText(chart.data.labels[index], xLine + extraLine + plusFivePx, yLine);
                        });
                    });
                }
            }
        }
    });

    return $.custom.graph;
});
