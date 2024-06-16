# Salutation Helpers

This helper provides utility methods to work with salutations.

## getNotSpecifiedSalutation

The `getNotSpecifiedSalutation` method return the salutation "Not specified" or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $salutation = $this->helper->Salutation()->getNotSpecifiedSalutation(); // [!code focus]
    }
}
```
