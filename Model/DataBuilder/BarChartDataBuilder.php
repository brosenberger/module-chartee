<?php

namespace BroCode\Chartee\Model\DataBuilder;

class BarChartDataBuilder extends AbstractChartDataBuilder
{
    protected function construct()
    {
        parent::construct();

        $this->setType('bar')
            ->addOption("scales", ["y" => ["beginAtZero" => true]]);
    }
}