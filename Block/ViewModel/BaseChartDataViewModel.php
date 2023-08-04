<?php

namespace BroCode\Chartee\Block\ViewModel;

use BroCode\Chartee\Api\ChartDataBuilderInterface;
use BroCode\Chartee\Api\ChartDataViewModelInterface;
use Magento\Framework\Serialize\Serializer\Json;

class BaseChartDataViewModel implements \BroCode\Chartee\Api\ChartDataViewModelInterface
{
    /**
     * @var Json
     */
    protected $serializer;
    /**
     * @var ChartDataBuilderInterface
     */
    private $dataBuilder;

    /**
     * @param Json $serializer
     * @param ChartDataBuilderInterface $dataBuilder
     */
    public function __construct(
        Json $serializer,
        ChartDataBuilderInterface $dataBuilder
    ) {
        $this->serializer = $serializer;
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * @return string
     */
    public function getConfigurationJson()
    {
        return $this->serializer->serialize(
            $this->dataBuilder->build()->getConfiguration()
        );
    }
}