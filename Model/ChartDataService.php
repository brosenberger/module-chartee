<?php

namespace BroCode\Chartee\Model;

use BroCode\Chartee\Api\ChartDataBuilderInterface;
use BroCode\Chartee\Api\Exception\CharteeException;

class ChartDataService
{
    /**
     * @var ChartDataBuilderInterface[]
     */
    protected $chartDataBuilders;

    /**
     * @param ChartDataBuilderInterface[] $chartDataBuilders
     */
    public function __construct(
        array $chartDataBuilders = []
    ) {
        $this->chartDataBuilders = $chartDataBuilders;
    }

    public function getChartData($chartBuilderName)
    {
        if (isset($this->chartDataBuilders[$chartBuilderName])) {
            return $this->chartDataBuilders[$chartBuilderName]->build();
        }

        throw new CharteeException('Chart builder not found');
    }

    public function getChartDataBuilder()
    {
        return $this->chartDataBuilders;
    }

}