<?php

namespace Magezil\CustomerBlock\Observer\DefaultValues;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class CustomerRegister
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CustomerRegister implements ObserverInterface
{
    private CustomerRepositoryInterface $customerRepository;
    private Settings $moduleSettings;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->moduleSettings->isEnabled()) {
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $observer->getEvent()->getData('customer');
            $customerId = $customer->getId();

            $customer = $this->customerRepository->getById($customerId);
            $customer->setCustomAttribute('is_blocked', $this->moduleSettings->getCustomerBlock())
                ->setCustomAttribute('can_purchase', $this->moduleSettings->getCustomerCanPurchase())
                ->setCustomAttribute('has_wishlist', $this->moduleSettings->getCustomerHasWishlist())
                ->setCustomAttribute('has_compare_list', $this->moduleSettings->getCustomerHasCompareList())
                ->setCustomAttribute('can_review', $this->moduleSettings->getCustomerCanReview());

            $this->customerRepository->save($customer);
        }
    }
}
