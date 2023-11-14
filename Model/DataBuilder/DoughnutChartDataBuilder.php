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

    protected function mergeConfigurations()
    {
        return parent::mergeConfigurations();
        // default sample from https://www.chartjs3.com/docs/chart/getting-started/
        return [
            "type" => $this->getType(),
            "data" => [
                "labels"=> $this->getDataLabels(),
                "datasets"=> [[
                    "label"=> $this->getDataSetLabel(),
                    "data"=> $this->getDataValues(),
                    "backgroundColor"=> [
                        'rgba(255, 26, 104, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(0, 0, 0, 0.2)'
                    ],
                    "borderColor"=> [
                        'rgba(255, 26, 104, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(0, 0, 0, 1)'
                    ],
                    "borderWidth"=> 1,
                    "cutout" => "90%",
                    "borderRadius"=> 20,
                    "offset" => 10
                ]]
            ],
            "options" => $this->getPlugins(),
            "plugins" => $this->getPlugins()
        ];
    }
}