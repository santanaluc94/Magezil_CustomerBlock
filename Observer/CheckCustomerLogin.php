<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CheckCustomerLogin implements ObserverInterface
{
    private $messageManager;
    private $customerSession;
    private $redirect;

    public function __construct(
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        RedirectInterface $redirect
    ) {
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if ($customer->getData('is_blocked')) {
            $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento.'));

            $this->customerSession->logout()
                ->setBeforeAuthUrl($this->redirect->getRefererUrl())
                ->setLastCustomerId($customer->getId());
        }
    }
}
