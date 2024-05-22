<?php

namespace SeePossible\CustomerOrderInfo\Api;

/**
 * Class CustomerOrderInfoInterface
 * @package SeePossible\CustomerOrderInfo\Api
 */
interface CustomerOrderInfoInterface
{
    /**
     * Get customer by Customer ID.
     *
     * @param int $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If customer with the specified ID does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($customerId);

}
