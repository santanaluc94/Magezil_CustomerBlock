# Customer Block

## Installation

To download the module by composer, execute this code bellow.

```sh
  composer require magezil/module-customer-block
```

### Requisitos do Sistema

> Requires at least: 2.4.0
>
> Tested up to: 2.4.0
>
> Requires PHP: 7.4
>
> Stable tag: 1.1
>
> Licenses: OSL-3.0/AFL-3.0

---

## Magento Admin

This module adds attributes that may or may not block customers individually for the following functionalities:

- Block customer to login
- Block customer to purchase order
- Block customer to add items in wishlist
- Block customer to add items in comparison list
- Block customer to review products

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/en_US/magezil-module.jpg)

To enable this module, follow these steps:
  - **Step 1:** Magento admin --> Stores -> Settings -> Configurations
  - **Step 2:** Tab _Magezil_ --> Section _Block Customer_ --> Group _Configurações Gerais_
  - **Step 3:** Enable Module = Sim

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/en_US/general-configurations.jpg)

### Block

To block some customer functionality, follow these steps below:
  - **Step 1:** Magento admin --> Customers --> All Customers --> _Customer_
  - **Step 2:** Account Information --> _Select attribute_
  - **Step 3:** Yes/No

### Default Value

This modules provides some settings in admin to set the default values in customer register.
  - **Customer is Blocked**: Will all customers, when they register, be able to login?
  - **Customer Can Purchase**: Will all customers, when they register, be able to purchase order?
  - **Customer Has Wishlist**: Will all customers, when they register, be able to add to wishlist?
  - **Customer Has Compare List**: Will all customers, when they register, be able to add to comparison list?
  - **Customer Can Review**: Will all customers, when they register, be able to review products?

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/en_US/default-values.jpg)

> For more information see the repository Wiki.
