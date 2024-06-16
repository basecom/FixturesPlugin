# Sales Channel Helpers

This helper provides utility methods to work with sales channels.

## getStorefrontSalesChannel

The `getStorefrontSalesChannel` method returns the first sales channel of type `Storefront` or null if it does not exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel(); // [!code focus]
    }
}
```

## getHeadlessSalesChannel

The `getHeadlessSalesChannel` method returns the first sales channel of type `Headless` or null if it does not exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salesChannel = $this->helper->SalesChannel()->getHeadlessSalesChannel(); // [!code focus]
    }
}
```

## getProductComparisonSalesChannel

The `getProductComparisonSalesChannel` method returns the first sales channel of type `Product Comparison` (in admin it is called Product Feed) or null if it does not exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salesChannel = $this->helper->SalesChannel()->getProductComparisonSalesChannel(); // [!code focus]
    }
}
```

## getSalesChannelByType

The `getSalesChannelByType` method takes a type parameter and returns the first sales channel of that specific type or null if it does not exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salesChannel = $this->helper->SalesChannel()->getSalesChannelByType( // [!code focus]
            Defaults::SALES_CHANNEL_TYPE_PRODUCT_COMPARISON // [!code focus]
        ); // [!code focus]
    }
}
```
