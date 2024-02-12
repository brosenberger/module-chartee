<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterfaceFactory;

use BroCode\Chartee\Api\GeoCodeJsonServiceInterface;
use BroCode\Chartee\Model\DataBuilder\AbstractChartDataBuilder;

class GeoChoroplethChartDataBuilder extends AbstractChartDataBuilder
{
    /** @var GeoCodeJsonServiceInterface  */
    protected $geoCodeJsonService;

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
}