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

/**
 * Class CheckPurchase
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CheckPurchase implements ObserverInterface
{
    private CheckoutSession $checkoutSession;
    private QuoteRepository $quoteRepository;
    private CustomerSession $customerSession;
    private CustomerRepositoryInterface $customerRepository;
    private Settings $moduleSettings;

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

            if (!!!$customer->getCustomAttribute('can_purchase')->getValue()) {
                $cart = $this->checkoutSession->getQuote();
                $cart->removeAllItems()->save()->collectTotals();

                $quote = $this->quoteRepository->getActive($cart->getId());
                $quote->setTotalsCollectedFlag(false)->collectTotals()->save();

                throw new AuthorizationException(__('This customer is blocked in admin Magento to purchase.'));
            }
        }
    }
}
