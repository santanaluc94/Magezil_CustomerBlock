<?php

namespace Magezil\CustomerBlock\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Settings
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class Settings
{
    private const MODULE_ENABLE = 'magezil_customer_block/general/enable';
    private const CUSTOMER_IS_BLOCKED = 'magezil_customer_block/default_values/is_blocked';
    private const CUSTOMER_CAN_PURCHASE = 'magezil_customer_block/default_values/can_purchase';
    private const CUSTOMER_HAS_WISHLIST = 'magezil_customer_block/default_values/has_wishlist';
    private const CUSTOMER_HAS_COMPARE_LIST = 'magezil_customer_block/default_values/has_compare_list';
    private const CUSTOMER_CAN_REVIEW = 'magezil_customer_block/default_values/can_review';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCustomerBlock(?int $storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_IS_BLOCKED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCustomerCanPurchase(?int $storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_CAN_PURCHASE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCustomerHasWishlist(?int $storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_HAS_WISHLIST,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCustomerHasCompareList(?int $storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_HAS_COMPARE_LIST,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCustomerCanReview(?int $storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_CAN_REVIEW,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
