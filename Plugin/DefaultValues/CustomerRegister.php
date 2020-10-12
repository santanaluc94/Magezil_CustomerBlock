<?php

namespace Magezil\CustomerBlock\Plugin\DefaultValues;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Message\ManagerInterface;
use Magezil\CustomerBlock\Model\Config\Settings;

class CustomerRegister
{
    public function __construct(
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        ManagerInterface $messageManager,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->messageManager = $messageManager;
        $this->moduleSettings = $moduleSettings;
    }

    public function afterExecute($subject): void
    {
        if ($this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            // var_dump(get_class_methods($subject));die;s

            $customer = $this->customerRepository->getById($customerId);

            $customer->setCustomAttribute('is_blocked', $this->moduleSettings->setCustomerBlock())
                ->setCustomAttribute('can_purchase', $this->moduleSettings->setCustomerCanPurchase())
                ->setCustomAttribute('has_wishlist', $this->moduleSettings->setCustomerHasWishlist())
                ->setCustomAttribute('has_compare_list', $this->moduleSettings->setCustomerHasCompareList())
                ->setCustomAttribute('can_review', $this->moduleSettings->setCustomerCanReview());

            $this->customerRepository->save($customer);
        }
    }
}
