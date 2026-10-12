define([
    'jquery',
    'uiRegistry',
    'graph'
], function ($, registry) {
    'use strict';

    /**
     * Restricts the customer group chart to the groups currently listed in the customer group grid.
     */
    return function (config, element) {
        var canvas = $(element).find('canvas'),
            groupIds = null;

        function apply() {
            if (groupIds !== null && canvas.data('customGraph')) {
                canvas.graph('filterDataSets', 'groupId', groupIds);
            }
        }

        canvas.on('graphcreate', apply);

        registry.get(config.provider, function (provider) {
            function update(data) {
                groupIds = ((data && data.items) || []).map(function (item) {
                    return String(item.customer_group_id);
                });
                apply();
            }

            provider.on('data', update);
            update(provider.get('data'));
        });
    };
});
