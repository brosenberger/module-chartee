<?php

namespace BroCode\Chartee\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class ChartRequestParameterService
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array|null
     */
    public function getStoreIds()
    {
        if ($this->getRequest()->getParam('store')) {
            return [$this->getRequest()->getParam('store')];
        } elseif ($this->getRequest()->getParam('website')) {
                return $this->storeManager->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
        } elseif ($this->getRequest()->getParam('group')) {
            return $this->storeManager->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
        }
        return null;
    }

    public function getStartDate()
    {
        if ($this->getRequest()->getParam('startDate')) {
            return $this->getRequest()->getParam('startDate');
        }
        return null;
    }

    public function getEndDate()
    {
        if ($this->getRequest()->getParam('endDate')) {
            return $this->getRequest()->getParam('endDate');
        }
        return null;
    }

    protected function getRequest()
    {
        return $this->request;
    }
}