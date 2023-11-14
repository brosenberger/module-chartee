<?php

namespace BroCode\Chartee\Model\DataBuilder;

class DoughnutChartDataBuilder extends AbstractChartDataBuilder
{


    protected function construct()
    {
        parent::construct();

        $this->setType('doughnut')
            ->addPlugin("doughnutLablesLine")
            ->addOption("layout", [
                "padding" => [
                    "top" => 20,
                    "bottom" => 20,
                    "left" => 50,
                    "right" => 50
                ]
            ])->addOption("plugins" , [
                "legend" => [
                    "display" => false
                ]
            ]);
    }

    public function addDefaultDataSetValues($dataSet)
    {
        return parent::addDefaultDataSetValues($dataSet)
            ->setBorderWidth(1)
            ->setCutout("90%")
            ->setBorderRadius(20)
            ->setOffset(10);
    }
}