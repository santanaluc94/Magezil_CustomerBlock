<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\AuthorizationException;

/**
 * Class CheckCustomerHasWishlist
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CheckCustomerHasWishlist implements ObserverInterface
{
    private CustomerSession $customerSession;
    private CustomerRepositoryInterface $customerRepository;
    private Settings $moduleSettings;

    public function __construct(
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer();
            $customerId = $customer->getId();
            $storeId = $customer->getStoreId();

            if ($this->moduleSettings->isEnabled($storeId)) {
                $customer = $this->customerRepository->getById($customerId);

                if (!!!$customer->getCustomAttribute('has_wishlist')->getValue()) {
                    $wishlist = $observer->getEvent()->getData('wishlist');
                    $items = $wishlist->getItemCollection();

                    foreach ($items as $item) {
                        $item->delete();
                        $wishlist->save();
                    }

                    throw new AuthorizationException(
                        __('This customer is blocked in admin Magento to add products in wishlist.')
                    );
                }
            }
        }
    }
}
