# Category Helpers

This helper provides utility methods to work with categories.

## getRootCategory

The `getRootCategory` method return the initial root category of the shop or null if none was found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $category = $this->helper->Category()->getRootCategory(); // [!code focus]
    }
}
```
