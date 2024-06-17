# Utility Methods
## ensureNotEmpty
The `ensureNotEmpty` method on the `FixtureHelper` checks that any given variable is not [empty](https://www.php.net/manual/en/function.empty.php). If it is, it will throw a `LogicException`.

This method also includes the necessary annotations so that [PHPStan](https://phpstan.org/) and [Psalm](https://psalm.dev/) don't throw any errors afterward:

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