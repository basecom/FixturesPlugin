# Currency Helpers

This helper provides utility methods to work with currencies.

## getCurrencyEuro

The `getCurrencyEuro` method returns the EURO currency, or null if it doesn't exist.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $euro = $this->helper->Currency()->getCurrencyEuro(); // [!code focus]
    }
}
```
