<?php

namespace Magezil\CustomerBlock\Observer\DefaultValues;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Message\ManagerInterface;
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

    public function execute(Observer $observer): void
    {
        if ($this->moduleSettings->isEnabled()) {

            $customerId = $observer->getEvent()->getCustomer()->getId();

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
