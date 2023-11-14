<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\Exception\CharteeDataException;

class GaugeChartDataBuilder extends AbstractChartDataBuilder
{
    /**
     * @var int
     */
    protected $needleValue = 0;
    /**
     * @var int
     */
    protected $maxValue = 100;

    /**
     * @var int
     */
    private $firstSector = 75;
    /**
     * @var int
     */
    private $thirdSector = 125;

    protected function construct()
    {
        parent::construct();
        $this->setType('doughnut')
            ->setSectors($this->firstSector, $this->thirdSector)
            ->addPlugin("gaugeNeedle")
            ->addOption("layout", [
                "padding" => [
                    "top" => 0,
                    "bottom" => 20,
                    "left" => 10,
                    "right" => 10
                ]
            ])->addOption("plugins", [
                "legend" => [
                    "display" => false
                ],
                "tooltip" => [
                    "yAlign" => "bottom",
                    "displayColors" => false
                ]
            ]);
    }

    public function setSectors($firstSector = 75, $thirdSector = 125)
    {
        if ($firstSector >= 100) {
            throw new CharteeDataException('First sector must be less than 100');
        }
        if ($thirdSector <= 100) {
            throw new CharteeDataException('Third sector must be greater than 100');
        }
        $this->firstSector = $firstSector;
        $this->thirdSector = $thirdSector;

        $this->setDataLabels([$firstSector . ' %',  '100 %', $thirdSector . ' %']);
        return $this;
    }

    public function setNeedleValue($needleValue = 0)
    {
        $this->needleValue = $needleValue;
        return $this;
    }

    protected function getNeedleValue() {
        return $this->needleValue;
    }

    public function setMaxValue($maxValue = 100)
    {
        $this->maxValue = $maxValue;
        $dataValues = $this->getDataValues();

        return $this->clearDataSets()
            ->createDataSet()
                ->setSumValue(array_reduce($dataValues, function ($carry, $item) {
                    return $carry + $item;
                }, 0))
                ->setDataValues(...$dataValues)
                ->build();
    }

    protected function getDataSets()
    {
        $dataSets = parent::getDataSets();
        foreach ($dataSets as &$dataSet) {
            $dataSet['needleValue'] = $this->getNeedleValue();
        }
        return $dataSets;
    }

    protected function getDataValues()
    {
        $firstValue = $this->maxValue * ($this->firstSector / 100);
        $secondValue = $this->maxValue;
        $thirdValue = $this->maxValue * ($this->thirdSector / 100);

        return [
            $firstValue,
            $secondValue - $firstValue,
            $thirdValue - $secondValue
        ];
    }

    public function addDefaultDataSetValues($dataSet)
    {
        return parent::addDefaultDataSetValues($dataSet)
            ->setBoderColor('white')
            ->setBorderWidth(2)
            ->setBorderRadius(5)
            ->setCutout('95%')
            ->setCircumference(180)
            ->setRotation(270);
    }

    protected function mergeConfigurations()
    {
        return [
            "type" => $this->getType(),
            "data" => [
                "labels"=> $this->getDataLabels(),
                "datasets"=> $this->getDataSets()
            ],
            "options" => $this->getOptions(),
            "plugins" => $this->getPlugins()
        ];
    }
}