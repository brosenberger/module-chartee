var config = {
    map: {
        '*': {
            'graph': 'BroCode_Chartee/js/graph',
            'graph-chartjs': 'BroCode_Chartee/js/chartjs-4.4.0.umd',
            'graph-chartjs-boxplot': 'BroCode_Chartee/js/chartjs-chart-boxplot-4.2.4.umd',
            'graph-chartjs-geo': 'BroCode_Chartee/js/chartjs-chart-geo-4.2.8.umd',
            'graph-chartjs-stacked100': 'BroCode_Chartee/js/chartjs-plugin-stacked100-1.5.2',
            'graph-chartjs-autocolors': 'BroCode_Chartee/js/chartjs-plugin-autocolors-0.2.2',
            'graph-chartjs-helpers': 'BroCode_Chartee/js/chartjs-helpers-parts-4.3.2',
            'date-range-picker-lib': 'BroCode_Chartee/js/daterangepicker-3.1',
            'date-range-picker': 'BroCode_Chartee/js/daterangepicker-widget'
        }
    },
    shim: {
        'date-range-picker-lib': ['jquery', 'moment']
    }
};