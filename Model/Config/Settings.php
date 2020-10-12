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
    const MODULE_ENABLE = 'magezil_customer_block/general/enable';
    const CUSTOMER_IS_BLOCKED = 'magezil_customer_block/default_values/is_blocked';
    const CUSTOMER_CAN_PURCHASE = 'magezil_customer_block/default_values/can_purchase';
    const CUSTOMER_HAS_WISHLIST = 'magezil_customer_block/default_values/has_wishlist';
    const CUSTOMER_HAS_COMPARE_LIST = 'magezil_customer_block/default_values/has_compare_list';
    const CUSTOMER_CAN_REVIEW = 'magezil_customer_block/default_values/can_review';

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_ENABLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function setCustomerBlock(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_IS_BLOCKED,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function setCustomerCanPurchase(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_CAN_PURCHASE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function setCustomerHasWishlist(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_HAS_WISHLIST,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function setCustomerHasCompareList(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_HAS_COMPARE_LIST,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function setCustomerCanReview(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CUSTOMER_CAN_REVIEW,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
