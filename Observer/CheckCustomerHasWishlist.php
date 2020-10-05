<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class CheckCustomerHasWishlist implements ObserverInterface
{
    private $customerSession;

    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    public function execute(Observer $observer): void
    {
        $customer = $this->customerSession->getCustomer();

        if (!$customer->getData('has_wishlist')) {

            $wishlist = $observer->getEvent()->getWishlist();
            $items = $wishlist->getItemCollection();

            foreach ($items as $item) {
                $item->delete();
                $wishlist->save();
            }

            throw new LocalizedException(__('This customer is blocked in admin Magento to add products in wishlist.'));
        }
    }
}
