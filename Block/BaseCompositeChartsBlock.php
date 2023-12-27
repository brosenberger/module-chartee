<?php

namespace BroCode\Chartee\Block;

use BroCode\Chartee\Api\DownloadLinkTemplateInterface;
use BroCode\Chartee\Api\DownloadLinkTemplateInterfaceFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class BaseCompositeChartsBlock extends Template
{
    const DEFAULT_CHARTBUILDER_NAME = 'chartDataBuilder';
    const VISIBILITY_CONFIG_PATH = 'visibilityConfigPath';
    const DOWNLOADNAME_CONFIG_PATH = 'downloadNameConfigPath';

    protected $chartBlock = [];
    /**
     * @var DownloadLinkTemplateInterfaceFactory
     */
    protected $downloadLinkTemplateFactory;

    /**
     * @param Context $context
     * @param DownloadLinkTemplateInterfaceFactory $downloadLinkTemplateFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \BroCode\Chartee\Api\DownloadLinkTemplateInterfaceFactory $downloadLinkTemplateFactory,
        array $data = [],
    ) {
        parent::__construct($context, $data);
        $this->downloadLinkTemplateFactory = $downloadLinkTemplateFactory;
    }

    public function getChartBlock($dataChartBuilderName = self::DEFAULT_CHARTBUILDER_NAME)
    {
        if (!isset($this->chartBlock[$dataChartBuilderName])) {
            $this->chartBlock[$dataChartBuilderName] = $this->getLayout()->createBlock(\BroCode\Chartee\Block\Widget\BaseChart::class,
                $this->getData($dataChartBuilderName).'_chart',
                ['data'=>['chartDataBuilder' => $this->getData($dataChartBuilderName)]]
            );
        }
        return $this->chartBlock[$dataChartBuilderName];
    }

    public function getChartData($dataElement = null, $dataChartBuilderName = self::DEFAULT_CHARTBUILDER_NAME)
    {
        $data = $this->getChartBlock($dataChartBuilderName)->getChartData()->getData();
        if ($dataElement!== null && $data !== null && isset($data[$dataElement])) {
            return $data[$dataElement];
        }

        return $data;
    }

    public function getDownloadDataLink()
    {
        /** @var DownloadLinkTemplateInterface $linkData */
        $linkData = $this->downloadLinkTemplateFactory->create();
        return $linkData->setDownloadFilenameConfigPath($this->getData(self::DOWNLOADNAME_CONFIG_PATH))
            ->setDownloadData($this->getDownloadData())
            ->toHtml();
    }

    protected function getDownloadData() {
        return [];
    }

    public function getCacheKeyInfo()
    {
        $cacheKey = parent::getCacheKeyInfo();
        $cacheKey[] = $this->getData(self::DEFAULT_CHARTBUILDER_NAME);
        return $cacheKey;
    }

    public function toHtml()
    {
        if ($this->getData(self::VISIBILITY_CONFIG_PATH)
            && !$this->_scopeConfig->getValue($this->getData(self::VISIBILITY_CONFIG_PATH))) {
            return '';
        }
        return parent::toHtml();
    }
}