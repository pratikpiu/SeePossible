<?php

namespace SeePossible\CustomerOrderInfo\Model;

use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\App\ResourceConnection;
use SeePossible\CustomerOrderInfo\Api\CustomerOrderInfoInterface;

/**
 * Class CustomerOrderInfo
 * @package SeePossible\CustomerOrderInfo\Model
 */
class CustomerOrderInfo implements CustomerOrderInfoInterface
{
     /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * Resource Model
     *
     * @var ResourceConnection
     */
    protected $resourceConnection;


    /**
     * @param CustomerRegistry $customerRegistry
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        CustomerRegistry $customerRegistry,
        ResourceConnection $resourceConnection
    )
    {
        $this->customerRegistry = $customerRegistry;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Get customer by Customer ID.
     *
     * @param int $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If customer with the specified ID does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($customerId)
    {
        $tableName = $this->resourceConnection->getTableName('sales_order');
        $connection = $this->resourceConnection->getConnection();
        $selectQuery = "SELECT SUM(grand_total) AS life_time_order_value FROM ".$tableName." WHERE customer_id = ".$customerId;
        $customeLifeTimeOrderValue = $connection->fetchAll($selectQuery);
        echo "Customer Life Time Order Value - " . $customeLifeTimeOrderValue[0]['life_time_order_value'];
        echo "\n";
        $customer = $this->customerRegistry->retrieve($customerId);
        return $customer->getDataModel();
    }
}
