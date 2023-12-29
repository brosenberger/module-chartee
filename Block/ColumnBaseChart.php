<?php

namespace BroCode\Chartee\Block;

use Magento\Backend\Block\Template;

class ColumnBaseChart extends Template
{
    protected $_template = 'BroCode_Chartee::column_base_chart.phtml';

    protected $_contentHtml = null;
    protected $_childCount = null;

    public function getSectionTitle()
    {
        return $this->getData('section_title');
    }

    public function getChildCount()
    {
        $this->getContentHtml();
        return $this->_childCount;
    }

    public function getContentHtml()
    {
        if ($this->_contentHtml === null) {
            $this->_childCount = 0;
            $layout = $this->getLayout();
            if (!$layout) {
                return '';
            }
            $contentHtml = '';
            foreach ($layout->getChildNames($this->getNameInLayout()) as $child) {
                $childHtml =  $layout->renderElement($child, true);
                if (!empty($childHtml)) {
                    $this->_childCount++;
                }
                $contentHtml .= $childHtml;
            }
            $this->_contentHtml = $contentHtml;
        }
        return $this->_contentHtml;
    }

    public function toHtml()
    {
        if ($this->getChildCount() === 0 && empty($this->getContentHtml())) {
            return '';
        }
        return parent::toHtml();
    }
}