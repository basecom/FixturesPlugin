# Payment Method Helpers

This helper provides utility methods to work with payment methods.

## getInvoicePaymentMethod

The `getInvoicePaymentMethod` method returns the default invoice payment method of Shopware, or null if it doesn't exist.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $paymentMethod = $this->helper->PaymentMethod()->getInvoicePaymentMethod(); // [!code focus]
    }
}
```
