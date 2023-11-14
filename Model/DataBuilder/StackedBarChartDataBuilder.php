<?php

namespace BroCode\Chartee\Model\DataBuilder;

class StackedBarChartDataBuilder extends BarChartDataBuilder
{

    protected function construct()
    {
        parent::construct();

        $this->addPlugin("stacked100")
            ->addOption('indexAxis', 'y')
            ->addOption('plugins', ['stacked100' => ['enable' => true]]);
    }
}