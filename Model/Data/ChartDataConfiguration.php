<?php

namespace BroCode\Chartee\Model\Data;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterface;

class ChartDataConfiguration implements ChartDataConfigurationInterface
{
    protected $configuration = [];
    protected $data = [];

    public function __construct($configuration = [], $data = [])
    {
        $this->configuration = $configuration;
        $this->data = $data;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getData()
    {
        return $this->data;
    }
}