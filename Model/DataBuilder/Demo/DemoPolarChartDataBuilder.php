<?php

namespace BroCode\Chartee\Model\DataBuilder\Demo;

use BroCode\Chartee\Model\DataBuilder\BarChartDataBuilder;

class DemoPolarChartDataBuilder extends BarChartDataBuilder
{

    protected function construct()
    {
        parent::construct();

        $this->setType('polarArea');
    }


    protected function mergeConfigurations()
    {
        return [
            "type" => $this->getType(),
            "data" => [
                "labels"=> $this->getDataLabels(),
                "datasets"=> $this->getDataSets()
            ],
            "options" => [
                "scales" => [
                    "y" => [
                        "beginAtZero" => true
                    ]
                ]
            ]
        ];
    }

    public function build()
    {
        $this->setDataLabels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->createDataSet()
                ->setLabel('Weekly Sales')
                ->setDataValues(12, 19, 3, 17, 28, 24, 7)
                ->setBackgroundColors("#2ecc71", "#3498db", "#95a5a6", "#9b59b6", "#f1c40f", "#e74c3c", "#34495e")
                ->build();

        return parent::build();
    }
}