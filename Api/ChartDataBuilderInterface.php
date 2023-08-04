<?php

namespace BroCode\Chartee\Api;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterface;

interface ChartDataBuilderInterface
{
    /**
     * @return ChartDataConfigurationInterface
     */
    public function build();
}