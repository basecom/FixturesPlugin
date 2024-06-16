# Getting started

The FixturePlugin for Shopware 6 provides convient commands and structure to create and manage
Fixture for your Shop Project or Plugin.

## What are Fixtures?
Summarized fixtures are a way to generate demo / example data for any given system. We borrowed
the term and general idea behind it from the [Symfony Fixtures](#todo). Generally speaking
you create a bunch of Fixtures on your shop which create products, customers, categories
and really anything else you need to run your shop locally.   
   
Of course fixtures can also be used in staging or review environments. We, at [basecom](https://basecom.de) even use it sometimes for production data, like e-mail template types, which can't be
created from the admin area.

## Installation
The plugin can simply be installed via composer into any existing Shopware shop:

```shell:no-line-numbers
# Install plugin via composer
composer require basecom/sw6-fixtures-plugin

# Reload the plugin table and install/activate the plugin
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

Alternatively you can download the newest release version from Github as a ZIP file and
install it via Admin into your shop: [All releases](https://github.com/basecom/FixturesPlugin/releases)

:::tip
To see a more in-depth tutorial about installing, see the dedicated [Installation](#todo) chapter.
:::

## Your first fixture
After installing the plugin you can begin writing your own fixtures. In this Getting started guide we will begin with a simple fixture, which creates a new customer. See the [Examples](#todo) page for inspiration.

Each fixture must extend the abstract `Fixture` class. You'll need to implement only one method: `load`. This would be a full example of how you can create a new customer.

Create a new file, located in an `Fixtures` subdirectory of your shop or plugin called: `CustomerFixture`:

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
See the [Writing first fixture](#todo) chapter to get a more in-depth guide on how to write fixtures.
:::

Each fixture must be [tagged](#todo) in the [symfony container](#todo) to be found by the FixturePlugin. We recommend to add a generic service definition which tags all classes which extend the `Fixture` class.

For this add this part to your services.yaml file:

```yaml
services:
    _instanceof:
        Basecom\FixturePlugin\Fixture:
            tags: ['basecom.fixture']
```

Finally you can run the fixture by executing the following [Symfony Command](#todo):

```shell:no-line-numbers
bin/console fixture:load
```

## Next Steps
Congratulations! You just wrote your first fixture :tada: 

Here are more useful resources:
- [A more detailed explanation about how to write fixtures](#todo)
- [All of our helper methods to make your life easier](#todo)
- [A lot of example fixture to help you get started](#todo)

Happy Coding!