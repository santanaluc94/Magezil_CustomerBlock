<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\QuoteRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\AuthorizationException;

class CheckPurchase implements ObserverInterface
{
    const CUSTOMER_CAN_PURCHASE = '0';

    private $checkoutSession;
    private $quoteRepository;
    private $customerSession;
    private $customerRepository;
    private $moduleSettings;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteRepository $quoteRepository,
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    public function execute(Observer $observer): void
    {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('can_purchase')->getValue() === self::CUSTOMER_CAN_PURCHASE) {
                $cart = $this->checkoutSession->getQuote();
                $cart->removeAllItems()->save()->collectTotals();

                $quote = $this->quoteRepository->getActive($cart->getId());
                $quote->setTotalsCollectedFlag(false)->collectTotals()->save();

                throw new AuthorizationException(__('This customer is blocked in admin Magento to purchase.'));
            }
        }
    }
}
