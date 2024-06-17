# Getting started

The FixturePlugin for Shopware 6 offers convenient commands and structures to create and manage fixtures for your shop project or plugin.

## What are Fixtures?
Fixtures are a method to generate demo or example data for any given system. We borrowed the concept and terminology from [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html). Essentially, fixtures allow you to create a set of predefined data for your shop, such as products, customers, categories, and anything else you need to run your shop locally.   
   
Fixtures can also be used in staging or review environments. At [basecom](https://basecom.de), we even use them sometimes for production data, such as email template types, which cannot be created from the admin area.

## Installation
You can easily install the plugin via Composer in any existing Shopware shop:

```shell:no-line-numbers
# Install the plugin via Composer
composer require basecom/sw6-fixtures-plugin

# Refresh the plugin list and install/activate the plugin
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

Alternatively, you can download the latest release version from GitHub as a ZIP file and install it via the Admin interface in your shop: [All releases](https://github.com/basecom/FixturesPlugin/releases)

:::tip
For a more detailed tutorial on installation, please see the dedicated [Installation](/installation) chapter.
:::

## Your first fixture
After installing the plugin, you can start writing your own fixtures. In this getting started guide, we will begin with a simple fixture that creates a new customer. For more inspiration, see the [Examples](/examples/index) page.

Each fixture must extend the abstract `Fixture` class and implement the `load` method. Below is a complete example of how to create a new customer.

Create a new file in the Fixtures subdirectory of your shop or plugin and name it `CustomerFixture.php`:

```php
<?php

namespace YourVendor\YourPlugin\Fixtures;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture
{
    private const CUSTOMER_ID = '0d8eefdd6d32456385580e2ff42431b9';
    private const ADDRESS_ID  = 'e27dc2b4e85f4a0f9a912a09f07701b0';

    public function __construct(
        private readonly EntityRepository $customerRepository
    ) {
    }

    public function load(): void
    {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel();
        $this->helper->ensureNotEmpty($salesChannel);

        $this->customerRepository->upsert([[
            'id'                     => self::CUSTOMER_ID,
            'salesChannelId'         => $salesChannel->getId(),
            'groupId'                => $salesChannel->getCustomerGroupId(),
            'defaultPaymentMethodId' => $this->helper->PaymentMethod()->getInvoicePaymentMethod()?->getId(),
            'defaultBillingAddress'  => [
                'id'           => self::ADDRESS_ID,
                'salutationId' => $this->helper->Salutation()->getNotSpecifiedSalutation()?->getId(),
                'firstName'    => 'Zoey',
                'lastName'     => 'Smith',
                'zipcode'      => '1234',
                'street'       => 'Sample Street',
                'city'         => 'Berlin',
                'countryId'    => $this->helper->LanguageAndLocale()->getCountry('DE')?->getId(),
            ],
            'defaultShippingAddressId' => self::ADDRESS_ID,
            'salutationId'             => $this->helper->Salutation()->getNotSpecifiedSalutation()?->getId(),
            'customerNumber'           => '1122',
            'firstName'                => 'Zoey',
            'lastName'                 => 'Smith',
            'email'                    => 'test@shopware.dev',
            'password'                 => 'notset',
        ]], Context::createDefaultContext());
    }
}
```

:::tip
For a more detailed guide on writing fixtures, see the [Writing first fixture](/writing/first-fixture) chapter.
:::

Each fixture must be [tagged](https://symfony.com/doc/current/service_container/tags.html) in the [symfony container](https://symfony.com/doc/current/service_container.html) to be recognized by the FixturePlugin. We recommend adding a generic service definition that tags all classes extending the `Fixture` class.

Add the following to your `services.yaml` file:

```yaml
services:
    _instanceof:
        Basecom\FixturePlugin\Fixture:
            tags: ['basecom.fixture']
```

Finally, you can run the fixture by executing the following [Symfony Command](https://symfony.com/doc/current/console.html):

```shell:no-line-numbers
bin/console fixture:load
```

## Next Steps
Congratulations! You've written your first fixture :tada: 

Here are some more useful resources to help you continue:
- [A more detailed explanation about how to write fixtures](/writing/first-fixture)
- [All of our helper methods to make your life easier](/writing/fixture-helper)
- [A lot of example fixture to help you get started](/examples/index)

Happy Coding!