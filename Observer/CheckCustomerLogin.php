<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
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
    const CUSTOMER_CAN_NOT_SIGN_IN = '1';

    private $messageManager;
    private $customerSession;
    private $redirect;
    private $customerRepository;
    private $moduleSettings;

    public function __construct(
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        RedirectInterface $redirect,
        CustomerRepositoryInterface $customerRepository,
        Settings $moduleSettings
    ) {
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $moduleSettings;
    }

    public function execute(Observer $observer): void
    {
        if ($this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('is_blocked')->getValue() === self::CUSTOMER_CAN_NOT_SIGN_IN) {
                $this->messageManager->addErrorMessage(__('This customer is blocked in admin Magento.'));

                $this->customerSession->logout()
                    ->setBeforeAuthUrl($this->redirect->getRefererUrl())
                    ->setLastCustomerId($customer->getId());
            }
        }
    }
}
