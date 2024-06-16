# Utility Methods
## ensureNotEmpty
The `ensureNotEmpty` method on the fixture helper checks that any given variable is not [empty](#todo). If it is, it will throw a `LogicException`.

This method also includes the needed annotations, so that [PHPstan](#todo) and [Psalm](#todo) don't throw any errors afterwards:

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel();
        $this->helper->ensureNotEmpty($salesChannel); // [!code focus]

        // Static code analysis now knows that `$salesChannel` exists and is not empty/null. // [!code focus]
        $salesChannel->getId();
    }
}
```