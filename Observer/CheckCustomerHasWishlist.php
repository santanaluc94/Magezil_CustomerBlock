<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\AuthorizationException;

class CheckCustomerHasWishlist implements ObserverInterface
{
    const CUSTOMER_CAN_NOT_USE_WISHLIST = '0';

    private $customerSession;
    private $customerRepository;
    private $moduleSettings;

    public function __construct(
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    public function execute(Observer $observer): void
    {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('has_wishlist')->getValue() === self::CUSTOMER_CAN_NOT_USE_WISHLIST) {

                $wishlist = $observer->getEvent()->getWishlist();
                $items = $wishlist->getItemCollection();

                foreach ($items as $item) {
                    $item->delete();
                    $wishlist->save();
                }

                throw new AuthorizationException(__('This customer is blocked in admin Magento to add products in wishlist.'));
            }
        }
    }
}
