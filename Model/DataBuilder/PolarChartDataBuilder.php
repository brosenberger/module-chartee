<?php

namespace BroCode\Chartee\Model\DataBuilder;

class PolarChartDataBuilder extends BarChartDataBuilder
{
    protected function construct()
    {
        parent::construct();
        $this->setType('polarArea')
            ->removeOption('scales');
    }
}