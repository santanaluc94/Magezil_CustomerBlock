<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\Product\Compare\ListCompare;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CheckCustomerHasCompareList implements ObserverInterface
{
    private $customerSession;
    private $compareList;
    private $messageManager;

    public function __construct(
        CustomerSession $customerSession,
        ListCompare $compareList,
        ManagerInterface $messageManager
    ) {
        $this->customerSession = $customerSession;
        $this->compareList = $compareList;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $subject): void
    {
        $customer = $this->customerSession->getCustomer();

        if (!$customer->getData('has_compare_list')) {
            $this->compareList->getItemCollection()
                ->setCustomerId($customer->getId())
                ->clear()
                ->save();

            $this->messageManager->getMessages(true);
            $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento to add products in compare list.'));
        }
    }
}
