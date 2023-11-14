<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\BarChartDataBuilder;
use BroCode\Chartee\Model\DataBuilder\StackedBarChartDataBuilder;

class DemoStackedBarChartDataBuilder extends StackedBarChartDataBuilder
{
    public function build()
    {
        // default sample from https://github.com/y-takey/chartjs-plugin-stacked100/#examples
        $this->setDataLabels(['Foo', 'Bar'])
            ->createDataSet()
                ->setLabel('Bad')
                ->setDataValues(5, 25)
                ->setBackgroundColors('rgba(244, 143, 177, 0.6)')
                ->build()
            ->createDataSet()
                ->setLabel('Better')
                ->setDataValues(15,10)
                ->setBackgroundColors('rgba(255, 235, 59, 0.6)')
                ->build()
            ->createDataSet()
                ->setLabel('Good')
                ->setDataValues(10,8)
                ->setBackgroundColors('rgba(100, 181, 246, 0.6)')
                ->build();

        return parent::build();
    }
}