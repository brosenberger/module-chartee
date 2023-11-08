<?php

namespace BroCode\Chartee\Model\DataBuilder;

class PolarChartDataBuilder extends BarChartDataBuilder
{

    protected function construct()
    {
        parent::construct();

        $this->setType('polarArea');
    }

    protected function mergeConfigurations()
    {
        $configurations = parent::mergeConfigurations();
        if (isset($configurations['options']['scales'])) {
            // if set, the scale grid would be printed
            unset($configurations['options']['scales']);
        }
        return $configurations;
    }
}