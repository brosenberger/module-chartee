<?php

namespace BroCode\Chartee\Controller\Adminhtml\Demo;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ACTION_RESOURCE = 'BroCode_Chartee::chartee';
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        PageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('BroCode_Chartee::chartee');
        $resultPage->getConfig()->getTitle()->prepend(__('Demo Charts'));
        $resultPage->addBreadcrumb(__('Content'), __('Content'));
        $resultPage->addBreadcrumb(__('BroCode Chartee'), __('BroCode Chartee'));
        return $resultPage;
    }
}