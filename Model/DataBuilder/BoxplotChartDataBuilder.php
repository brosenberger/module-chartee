<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\ChartDataBuilderInterface;

class BoxplotChartDataBuilder extends AbstractChartDataBuilder implements ChartDataBuilderInterface
{

    protected function construct()
    {
        parent::construct();

        $this->setType('boxplot')
            ->addOption("scales", ["y" => ["beginAtZero" => true]]);
    }

    public function addDefaultDataSetValues($dataSet)
    {
        return parent::addDefaultDataSetValues($dataSet)
            ->setPadding(0)
            ->setItemRadius(2);
    }
}