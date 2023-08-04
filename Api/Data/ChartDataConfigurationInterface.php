<?php

namespace BroCode\Chartee\Api\Data;

use Magento\Framework\View\Element\Block\ArgumentInterface;

interface ChartDataConfigurationInterface extends ArgumentInterface
{
    /**
     * @return array
     */
    public function getConfiguration();
}