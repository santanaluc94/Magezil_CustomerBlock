<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\Product\Compare\ListCompare;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CheckCustomerHasCompareList implements ObserverInterface
{
    const CUSTOMER_CAN_NOT_USE_COMPARE_LIST = '0';

    private $customerSession;
    private $compareList;
    private $messageManager;
    private $customerRepository;
    private $moduleSettings;

    public function __construct(
        CustomerSession $customerSession,
        ListCompare $compareList,
        ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->compareList = $compareList;
        $this->messageManager = $messageManager;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    public function execute(Observer $observer): void
    {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('has_compare_list')->getValue() === self::CUSTOMER_CAN_NOT_USE_COMPARE_LIST) {
                $this->compareList->getItemCollection()
                    ->setCustomerId($customer->getId())
                    ->clear()
                    ->save();

                $this->messageManager->getMessages(true);
                $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento to add products in compare list.'));
            }
        }
    }
}
