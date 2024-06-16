# Currency Helpers

This helper provides utility methods to work with currencies.

## getCurrencyEuro

The `getCurrencyEuro` method return the EURO currency or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $euro = $this->helper->Currency()->getCurrencyEuro(); // [!code focus]
    }
}
```
