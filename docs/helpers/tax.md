# Tax Helpers

This helper provides utility methods to work with taxes.

## getTax19

The `getTax19` method returns the tax entity with a tax rate of 19% or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $tax = $this->helper->Tax()->getTax19(); // [!code focus]
    }
}
```

## getTax

The `getTax` method takes a tax rate as parameter and returns the tax entity with that given tax rate or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $tax = $this->helper->Tax()->getTax(16.0); // [!code focus]
    }
}
```
