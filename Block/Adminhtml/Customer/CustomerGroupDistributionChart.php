<?php

namespace BroCode\Chartee\Block\Adminhtml\Customer;

use BroCode\Chartee\Api\Constants;
use BroCode\Chartee\Block\Adminhtml\BaseCompositeChartsBlock;

class CustomerGroupDistributionChart extends BaseCompositeChartsBlock
{
    protected $_template = 'BroCode_Chartee::customer/customer_group_distribution.phtml';

    protected function _construct()
    {
        parent::_construct();
        $this->setData(self::DEFAULT_CHARTBUILDER_NAME, 'brocode-customer-stacked-group');
        $this->setData(self::VISIBILITY_CONFIG_PATH, Constants::CONFIG_CUSTOMER_GROUPDISTRIBUTIONCHART);
        $this->setData(self::DOWNLOADNAME_CONFIG_PATH, Constants::CONFIG_CUSTOMER_GROUPDISTRIBUTIONCHART_DOWNLOADFILE);
        $this->setData(self::PERMISSION, Constants::PERMISSION_CUSTOMER_GROUPDISTRIBUTION);
    }

    protected function getDownloadData()
    {
        return $this->getChartData('customer_groups_data');
    }
}