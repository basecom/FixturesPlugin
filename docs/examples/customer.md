---
example: 'Create a customer'
---

# Create a customer

Here is an example fixture on how to create a new shopware customer. This fixture creates the customer, the associated addresses and sets a password (to `password`):

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

## Used in this example:
- [Command to generate the UUIDs](#todo)
- [`getStorefrontSalesChannel` helper method](#todo)
- [`ensureNotEmpty` helper method](#todo)
- [`getInvoicePaymentMethod` helper method](#todo)
- [`getNotSpecifiedSalutation` helper method](#todo)
- [`getCountry` helper method](#todo)