<?php

namespace BroCode\Chartee\Model\Data;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterface;

class ChartDataConfiguration implements ChartDataConfigurationInterface
{
    protected $configuration = [];

    public function __construct($configuration = [])
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }
}