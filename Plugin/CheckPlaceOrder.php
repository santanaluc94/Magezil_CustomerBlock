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

/**
 * Class CheckPlaceOrder
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CheckPlaceOrder
{
    private CustomerSession $customerSession;
    private CheckoutSession $checkoutSession;
    private CustomerRepositoryInterface $customerRepository;
    private QuoteRepository $quoteRepository;
    private RedirectFactory $resultRedirectFactory;
    private RedirectInterface $redirect;
    private ManagerInterface $messageManager;
    private Settings $moduleSettings;

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

    /**
     * @param PaymentInformationManagement $subject
     * @return Redirect|void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagement $subject
    ) {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {
            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if (!!!$customer->getCustomAttribute('can_purchase')->getValue()) {
                $cart = $this->checkoutSession->getQuote();
                $cart->removeAllItems()->save()->collectTotals();

                $quote = $this->quoteRepository->getActive($cart->getId());
                // @phpstan-ignore-next-line
                $quote->setTotalsCollectedFlag(false)->collectTotals()->save();

                $this->messageManager->addErrorMessage(
                    __('This customer is blocked in admin Magento to purchase.')
                );

                $result = $this->resultRedirectFactory->create();
                return $result->setUrl($this->redirect->getRefererUrl());
            }
        }
    }
}
