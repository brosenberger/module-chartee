<?php

namespace BroCode\Chartee\Block;

use Magento\Backend\Block\Template;

class ColumnBaseChart extends Template
{
    protected $_template = 'BroCode_Chartee::column_base_chart.phtml';
    public function getSectionTitle()
    {
        return $this->getData('section_title');
    }
}