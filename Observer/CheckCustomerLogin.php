<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class CheckCustomerLogin
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CheckCustomerLogin implements ObserverInterface
{
    private ManagerInterface $messageManager;
    private CustomerSession $customerSession;
    private RedirectInterface $redirect;
    private CustomerRepositoryInterface $customerRepository;
    private Settings $moduleSettings;
    private StoreManagerInterface $storeManager;

    public function __construct(
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        RedirectInterface $redirect,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings,
        StoreManagerInterface $storeManager
    ) {
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        $storeId = $this->storeManager->getStore()->getId();

        if ($this->moduleSettings->isEnabled($storeId)) {
            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if (!!$customer->getCustomAttribute('is_blocked')->getValue()) {
                $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento.'));
                $this->customerSession->logout() // @phpstan-ignore-line
                    ->setBeforeAuthUrl($this->redirect->getRefererUrl())
                    ->setLastCustomerId($customer->getId());
            }
        }
    }
}
