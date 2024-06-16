# Payment Method Helpers

This helper provides utility methods to work with payment methods.

## getInvoicePaymentMethod

The `getInvoicePaymentMethod` method return the default invoice payment method of shopware or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $paymentMethod = $this->helper->PaymentMethod()->getInvoicePaymentMethod(); // [!code focus]
    }
}
```
