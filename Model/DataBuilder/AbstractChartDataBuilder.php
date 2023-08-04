<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\ChartDataBuilderInterface;
use BroCode\Chartee\Api\Data\ChartDataConfigurationInterfaceFactory;

abstract class AbstractChartDataBuilder implements ChartDataBuilderInterface
{
    protected $dataLabels = [];

    protected $type = '';

    protected $options = [];

    protected $plugins = [];

    protected $dataSets = [];
    /**
     * @var ChartDataConfigurationInterfaceFactory
     */
    private $chartDataConfigurationFactory;
    /**
     * @var DataSetBuilderFactory
     */
    private $dataSetBuilderFactory;

    /**
     * @param ChartDataConfigurationInterfaceFactory $chartDataConfigurationFactory
     */
    public function __construct(
        ChartDataConfigurationInterfaceFactory $chartDataConfigurationFactory,
        DataSetBuilderFactory $dataSetBuilderFactory
    )
    {
        $this->chartDataConfigurationFactory = $chartDataConfigurationFactory;
        $this->dataSetBuilderFactory = $dataSetBuilderFactory;
        $this->construct();
    }

    protected function construct()
    {
    }

    public function build()
    {
        $data =  $this->chartDataConfigurationFactory->create(
            [
                'configuration' => $this->mergeConfigurations()
            ]
        );
        $this->resetData();
        return $data;
    }

    /**
     * @return array
     */
    abstract protected function mergeConfigurations();

    /**
     * @return void
     */
    protected function resetData()
    {
        $this->dataLabels = [];
        $this->options = [];
        $this->plugins = [];
        $this->type = '';
    }

    public function addPlugin($plugin)
    {
        $this->plugins[] = $plugin;
        return $this;
    }

    protected function getPlugins()
    {
        return $this->plugins;
    }

    public function createDataSet($data = [], $addDefaults = true) {
        $dataSet = $this->dataSetBuilderFactory->create([
            'builder' => $this,
            'data' => $data
        ]);
        return $addDefaults ? $this->addDefaultDataSetValues($dataSet) : $dataSet;
    }

    public function addDefaultDataSetValues($dataSet)
    {
        return $dataSet;
    }

    public function addDataSet(array $data)
    {
        $this->dataSets[] = $data;
        return $this;
    }

    public function clearDataSets()
    {
        $this->dataSets = [];
        return $this;
    }

    protected function getDataSets()
    {
        return $this->dataSets;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    protected function getType()
    {
        return $this->type;
    }

    public function setDataLabels($dataLabels = [])
    {
        $this->dataLabels = $dataLabels;
        return $this;
    }

    protected function getDataLabels()
    {
        return $this->dataLabels;
    }
}