<?php

namespace BroCode\Chartee\Block\Adminhtml\Listing;

use BroCode\Chartee\Model\ChartDataService;
use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class DataBuilderOverview extends Template
{
    protected $_template = 'BroCode_Chartee::databuilder_overview.phtml';
    /**
     * @var ChartDataService
     */
    protected $chartDataService;

    public function __construct(
        ChartDataService $chartDataService,
        Template\Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->chartDataService = $chartDataService;
    }

    public function getChartDataBuilders()
    {
        return $this->chartDataService->getChartDataBuilder();
    }
}