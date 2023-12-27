<?php

namespace BroCode\Chartee\Block\Adminhtml;

use BroCode\Chartee\Api\DownloadLinkTemplateInterfaceFactory;
use Magento\Framework\AuthorizationInterface;

class BaseCompositeChartsBlock extends \BroCode\Chartee\Block\BaseCompositeChartsBlock
{
    const PERMISSION = 'permission';

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * @param DownloadLinkTemplateInterfaceFactory $downloadLinkTemplateFactory
     * @param array $data
     */
    public function __construct(
        AuthorizationInterface $authorization,
        \Magento\Framework\View\Element\Template\Context $context,
        \BroCode\Chartee\Api\DownloadLinkTemplateInterfaceFactory $downloadLinkTemplateFactory,
        array $data = []
    ) {
        parent::__construct($context, $downloadLinkTemplateFactory, $data);
        $this->authorization = $authorization;
    }

    public function toHtml()
    {
        if ($this->getData(self::PERMISSION)
            && !$this->authorization->isAllowed($this->getData(self::PERMISSION))) {
            return '';
        }
        return parent::toHtml();
    }
}