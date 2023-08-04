<?php

namespace BroCode\Chartee\Model\DataBuilder;

class DoughnutChartDataBuilder extends AbstractChartDataBuilder
{
    /**
     * @var string
     */
    protected $dataSetLabel = '';

    /**
     * @var array
     */
    protected $dataValues = [];

    public function setDataValues($dataValues = [])
    {
        $this->dataValues = $dataValues;
        return $this;
    }

    protected function getDataValues()
    {
        return $this->dataValues;
    }

    public function setDataSetLabel($dataSetLabel)
    {
        $this->dataSetLabel = $dataSetLabel;
        return $this;
    }

    protected function getDataSetLabel()
    {
        return $this->dataSetLabel;
    }


    protected function construct()
    {
        parent::construct();

        $this->setType('doughnut')
            ->addPlugin("doughnutLablesLine");
    }

    protected function mergeConfigurations()
    {
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
            "options" => [
                "layout" => [
                    "padding" => [
                        "top" => 20,
                        "bottom" => 20,
                        "left" => 50,
                        "right" => 50
                    ]
                ],
                "plugins" => [
                    "legend" => [
                        "display" => false
                    ]
                ]
            ],
            "plugins" => $this->getPlugins()
        ];
    }
}