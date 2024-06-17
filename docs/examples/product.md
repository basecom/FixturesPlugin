---
example: 'Create a product'
---

# Create a product

Here is an example fixture on how to create a new shopware product. This fixture creates the product and fills out all the required fields:

```php
<?php

namespace YourVendor\YourPlugin\Fixtures;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Basecom\FixturePlugin\Fixture;

class ProductFixture extends Fixture
{
    public const PRODUCT_ID = '0bea2ae3509b4ff19bf24719fadb106f';
    public const VISIBILITY_ID = '5f3bae78b6f547c29cd4b94104da3acf';

    public function __construct(
        private readonly EntityRepository $productRepository,
    ) {
    }

    public function load(): void
    {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel();
        $this->helper->ensureNotEmpty($salesChannel);

        $rootCategory = $this->helper->Category->getRootCategory();
        $this->helper->ensureNotEmpty($rootCategory);

        $this->productRepository->upsert([[
            'id'            => self::PRODUCT_ID,
            'name'          => 'Example Product',
            'active'        => true,
            'productNumber' => '1234',
            'taxId'         => $this->helper->Tax()->getTax19()?->getId(),
             'price'        => [
                $this->helper->SalesChannel()->getCurrencyEuro()?->getId() => [
                    'net'        => 84.03,
                    'gross'      => 100,
                    'linked'     => true,
                    'currencyId' => $this->helper->SalesChannel()->getCurrencyEuro()?->getId(),
                ],
            ],
            'stock'         => 100,
            'categories'    => [['id' => $rootCategory->id]],
            'visibilities'  => [
                [
                    'id'             => self::VISIBILITY_ID,
                    'productId'      => self::PRODUCT_ID,
                    'salesChannelId' => $salesChannel->getId(),
                    'visibility'     => 30,
                ],
            ],
        ]], Context::createDefaultContext());
    }
}
```

## Used in this example:
- [Command to generate the UUIDs](/writing/available-commands#get-random-uuid)
- [`getStorefrontSalesChannel` helper method](/helpers/sales-channel#getstorefrontsaleschannel)
- [`ensureNotEmpty` helper method](/helpers/utility#ensurenotempty)
- [`getRootCategory` helper method](/helpers/category#getrootcategory)
- [`getTax19` helper method](/helpers/tax.html#gettax19)
- [`getCurrencyEuro` helper method](/helpers/currency#getcurrencyeuro)
