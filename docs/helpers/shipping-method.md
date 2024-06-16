# Shipping Method Helpers

This helper provides utility methods to work with shipping methods.

## getFirstShippingMethod

The `getFirstShippingMethod` method simply returns the first active shipping method or null if none exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $shippingMethod = $this->helper->ShippingMethod()->getFirstShippingMethod(); // [!code focus]
    }
}
```
