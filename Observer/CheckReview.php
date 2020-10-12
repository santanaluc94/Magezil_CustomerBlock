<?php

namespace Magezil\CustomerBlock\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magezil\CustomerBlock\Model\Config\Settings;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class CheckReview
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class CheckReview implements ObserverInterface
{
    const CUSTOMER_CAN_PURCHASE = '0';

    private $customerSession;
    private $customerRepository;
    private $messageManager;
    private $moduleSettings;

    public function __construct(
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        ManagerInterface $messageManager,
        Settings $moduleSettings
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->messageManager = $messageManager;
        $this->moduleSettings = $moduleSettings;
    }

    public function execute(Observer $observer): void
    {
        if ($this->customerSession->isLoggedIn() && $this->moduleSettings->isEnabled()) {

            $customerId = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerRepository->getById($customerId);

            if ($customer->getCustomAttribute('can_review')->getValue() === self::CUSTOMER_CAN_PURCHASE) {

                $messageError = __('This customer is blocked in admin Magento to review.');
                $this->messageManager->addErrorMessage($messageError);
                throw new LocalizedException($messageError);
            }
        }
    }
}
