<?php

namespace BroCode\Chartee\Api;

use Magento\Framework\View\Element\Block\ArgumentInterface;

interface ChartDataViewModelInterface extends ArgumentInterface
{
    /**
     * @return string
     */
    public function getConfigurationJson();

}