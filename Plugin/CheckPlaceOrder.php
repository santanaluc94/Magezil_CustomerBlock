<?php

namespace Magezil\CustomerBlock\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Message\ManagerInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Checkout\Model\PaymentInformationManagement;

class CheckPlaceOrder
{
    const CUSTOMER_CAN_PURCHASE = '0';

    private $customerSession;
    private $checkoutSession;
    private $customerRepository;
    private $quoteRepository;
    private $resultRedirectFactory;
    private $redirect;
    private $messageManager;
    private $moduleSettings;

    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        CustomerRepositoryInterface $customerRepository,
        QuoteRepository $quoteRepository,
        RedirectFactory $resultRedirectFactory,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->customerRepository = $customerRepository;
        $this->quoteRepository = $quoteRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
        $this->moduleSettings = $moduleSettings;
    }

    public function beforeSavePaymentInformationAndPlaceOrder(PaymentInformationManagement $subject): Redirect
    {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('can_purchase')->getValue() === self::CUSTOMER_CAN_PURCHASE) {
                $cart = $this->checkoutSession->getQuote();
                $cart->removeAllItems()->save()->collectTotals();

                $quote = $this->quoteRepository->getActive($cart->getId());
                $quote->setTotalsCollectedFlag(false)->collectTotals()->save();

                $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento to purchase.'));

                $result = $this->resultRedirectFactory->create();
                return $result->setUrl($this->redirect->getRefererUrl());
            }
        }
    }
}
