# Salutation Helpers

This helper provides utility methods to work with salutations.

## getNotSpecifiedSalutation

The `getNotSpecifiedSalutation` method returns the salutation "Not specified", or null if it doesn't exist.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salutation = $this->helper->Salutation()->getNotSpecifiedSalutation(); // [!code focus]
    }
}
```
