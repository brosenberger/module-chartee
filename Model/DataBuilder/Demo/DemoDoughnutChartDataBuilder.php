<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\DoughnutChartDataBuilder;

class DemoDoughnutChartDataBuilder extends DoughnutChartDataBuilder
{
    public function build()
    {
        // sample from https://www.youtube.com/watch?v=YcRj52VovYQ
        $this->setDataLabels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->createDataSet()
                ->setLabel('Weekly Sales')
                ->setDataValues(18, 12, 6, 9, 12, 3, 9)
                ->setBackgroundColor([
                    'rgba(255, 26, 104, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(0, 0, 0, 0.2)'
                ])->setBorderColor([
                    'rgba(255, 26, 104, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(0, 0, 0, 1)'
                ])
                ->build();

        return parent::build();
    }
}