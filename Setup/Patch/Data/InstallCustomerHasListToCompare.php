<?php

namespace Magezil\CustomerBlock\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;

/**
 * Class InstallCustomerHasListToCompare
 *
 * @category Magento
 * @package  Magezil_CustomerBlock
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @license  OSL-3.0
 * @license  AFL-3.0
 * @link     http://github.com/santanaluc94
 */
class InstallCustomerHasListToCompare implements DataPatchInterface
{
    private const ATTR_NAME = "has_compare_list";

    private ModuleDataSetupInterface $moduleDataSetup;
    private CustomerSetupFactory $customerSetupFactory;
    private SetFactory $attributeSetFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        SetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function apply(): void
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ATTR_NAME,
            [
                'type' => 'int',
                'label' => 'Customer Has Compare List',
                'input' => 'select',
                'source' => Boolean::class,
                'required' => false,
                'sort_order' => 3,
                'position' => 3,
                'visible' => true,
                'user_defined' => true,
                'unique' => false,
                'system' => false,
                'default' => 1
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(
            Customer::ENTITY,
            self::ATTR_NAME
        );

        $attribute->addData(
            [
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
            ]
        );

        $attribute->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
