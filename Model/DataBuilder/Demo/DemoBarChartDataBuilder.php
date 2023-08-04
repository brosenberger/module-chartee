<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\BarChartDataBuilder;

class DemoBarChartDataBuilder extends BarChartDataBuilder
{
    public function build()
    {
        // default sample from https://www.chartjs3.com/docs/chart/getting-started/
        $this->setDataLabels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->setDataSetLabel('Weekly Sales')
            ->setDataValues([18, 12, 6, 9, 12, 3, 9]);

        return parent::build();
    }
}