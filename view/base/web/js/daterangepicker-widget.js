
define(['jquery', 'moment', 'mage/translate', 'date-range-picker-lib'], function ($, moment, $t) {
    var daterangepicker = function(config, node) {

        $(document).ready(function () {
            var inputField =  $(node);
            var params = (new URL(document.location)).searchParams;
            var ranges = {};
            ranges[$t('Today')] = [moment(), moment()];
            ranges[$t('Yesterday')] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
            ranges[$t('Last 7 Days')] = [moment().subtract(6, 'days'), moment()];
            ranges[$t('Last 30 Days')] = [moment().subtract(29, 'days'), moment()];
            ranges[$t('This Month')] = [moment().startOf('month'), moment().endOf('month')];
            ranges[$t('Last Month')] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
            var options = {
                autoUpdateInput: false,
                showDropdowns: true,
                showWeekNumbers: true,
                alwaysShowCalendars: true,
                ranges: ranges,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: $t('Clear'),
                    "applyLabel": $t("Apply"),
                    "fromLabel": $t("From"),
                    "toLabel": $t("To"),
                    "customRangeLabel": $t("Custom Range"),
                    "weekLabel": $t("W"),
                    "daysOfWeek": [$t("Su"), $t("Mo"), $t("Tu"), $t("We"), $t("Th"), $t("Fr"), $t("Sa")],
                    "monthNames": [$t("January"), $t("February"), $t("March"), $t("April"), $t("May"), $t("June"),
                        $t("July"), $t("August"), $t("September"), $t("October"), $t("November"), $t("December")],
                }
            }
            var inputVal = '';
            if (params.get('startDate')) {
                options.startDate = params.get('startDate');
                inputVal = params.get('startDate');
            }
            if (params.get('endDate')) {
                options.endDate = params.get('endDate');
                inputVal += ' - ' + params.get('endDate');
            }
            inputField.val(inputVal);
            inputField.daterangepicker(options,
                function(start, end, label) {
                    var params = (new URL(document.location)).searchParams;
                    if (!start) {
                        params.delete('startDate');
                    } else {
                        params.set('startDate', start.format('YYYY-MM-DD'));
                    }
                    if (!end) {
                        params.delete('endDate');
                    } else {
                        params.set('endDate', end.format('YYYY-MM-DD'));
                    }
                    inputField.val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    var search = params.toString();
                    if (search) {
                        search = '?' + search;
                    }
                    window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + search;
                });
            inputField.on('cancel.daterangepicker', function(ev, picker) {
                var params = (new URL(document.location)).searchParams;
                params.delete('startDate');
                params.delete('endDate');
                var search = params.toString();
                if (search) {
                    search = '?' + search;
                }
                window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + search;
                $(this).val('');
            });
        });
    }
    return daterangepicker;

});