<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterfaceFactory;

use BroCode\Chartee\Api\GeoCodeJsonServiceInterface;
use BroCode\Chartee\Model\DataBuilder\AbstractChartDataBuilder;

class GeoChoroplethChartDataBuilder extends AbstractChartDataBuilder
{
    /** @var GeoCodeJsonServiceInterface  */
    protected $geoCodeJsonService;

    protected $topojson = null;

    public function __construct(
        ChartDataConfigurationInterfaceFactory $chartDataConfigurationFactory,
        DataSetBuilderFactory $dataSetBuilderFactory,
        GeoCodeJsonServiceInterface $geoCodeJsonService
    ) {
        parent::__construct($chartDataConfigurationFactory, $dataSetBuilderFactory);
        $this->geoCodeJsonService = $geoCodeJsonService;
    }

    protected function construct()
    {
        parent::construct();
        $this->setType('choropleth');
    }

    /**
     * Topology the dataset values refer to: each data entry is ['feature' => <name or id>, 'value' => <number>],
     * the browser resolves the references to the GeoJSON features of the given topology object.
     *
     * @param string $name registered geocode json, e.g. countries-50m
     * @param string $object topology object, e.g. countries
     * @return $this
     */
    public function setTopology($name, $object)
    {
        $this->topojson = [
            'topology' => $this->geoCodeJsonService->getGeoCodeJson($name),
            'object' => $object
        ];
        return $this;
    }

    protected function mergeConfigurations()
    {
        $configuration = parent::mergeConfigurations();
        if ($this->topojson !== null) {
            $configuration['topojson'] = $this->topojson;
        }
        return $configuration;
    }

    protected function resetData()
    {
        parent::resetData();
        $this->topojson = null;
    }
}
