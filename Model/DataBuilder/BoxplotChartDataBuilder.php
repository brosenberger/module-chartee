<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\ChartDataBuilderInterface;

class BoxplotChartDataBuilder extends AbstractChartDataBuilder implements ChartDataBuilderInterface
{
    protected $dataRows = [];
    protected $dataSetLabel = '';

    protected function construct()
    {
        parent::construct();

        $this->setType('boxplot');
    }

    public function addDefaultDataSetValues($dataSet)
    {
        return parent::addDefaultDataSetValues($dataSet)
            ->setPadding(0)
            ->setItemRadius(2)
            ->setBorderWidth(1);
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

    protected function resetData()
    {
        parent::resetData();
        $this->dataRows = [];
        $this->dataSetLabel = '';
    }

    public function setDataSetLabel($dataSetLabel = '')
    {
        $this->dataSetLabel = $dataSetLabel;
        return $this;
    }

    protected function getDataSetLabel()
    {
        return $this->dataSetLabel;
    }

    public function addDataRow($dataRow = [])
    {
        $this->dataRows[] = $dataRow;
        return $this;
    }

    protected function getDataRows()
    {
        return $this->dataRows;
    }

}