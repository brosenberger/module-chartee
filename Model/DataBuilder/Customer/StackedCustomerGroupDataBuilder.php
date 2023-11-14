<?php

namespace BroCode\Chartee\Model\DataBuilder\Customer;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterfaceFactory;
use BroCode\Chartee\Model\DataBuilder\DataSetBuilderFactory;
use BroCode\Chartee\Model\DataBuilder\StackedBarChartDataBuilder;
use Magento\Customer\Model\GroupRegistry;
use Magento\Reports\Model\ResourceModel\Customer\CollectionFactory;

class StackedCustomerGroupDataBuilder extends StackedBarChartDataBuilder
{
    private CollectionFactory $collectionFactory;
    private GroupRegistry $groupRegistry;

    public function __construct(
        CollectionFactory $collectionFactory,
        GroupRegistry $groupRegistry,
        ChartDataConfigurationInterfaceFactory $chartDataConfigurationFactory,
        DataSetBuilderFactory $dataSetBuilderFactory
    ) {
        parent::__construct($chartDataConfigurationFactory, $dataSetBuilderFactory);
        $this->collectionFactory = $collectionFactory;
        $this->groupRegistry = $groupRegistry;
    }

    public function build()
    {
        /** @var \Magento\Reports\Model\ResourceModel\Customer\Collection $collection */
        $collection = $this->collectionFactory->create();
        $connection = $collection->getResource()->getConnection();
        $groupData = $connection->fetchAll($this->getSelectQuery(
            $connection,
            $collection->getResource()->getEntityTable())
        );

        $this->setDataLabels([__('Customer Groups')]);
        foreach ($groupData as &$group) {
            $group['group_name'] = $this->groupRegistry->retrieve($group['group_id'])->getCode();
            $this->createDataSet()
                ->setLabel($group['group_name'])
                ->setDataValues($group['count'])
                ->build();
        }

        $this->setData('customer_groups_data', $groupData);

        $this->addOption("plugins" , [
            "legend" => [
                "display" => false
            ]
        ])->addOption('maintainAspectRatio', false);

        return parent::build();
    }

    public function getSelectQuery($connection, $table) {
        $select = $connection->select();
        $select->from($table)->reset('columns')
            ->columns(['group_id', 'count' => 'COUNT(*)'])
            ->group('group_id');
        return $select;
    }
}