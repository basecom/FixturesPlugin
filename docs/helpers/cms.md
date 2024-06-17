# CMS Helpers

This helper provides utility methods to work with CMS pages and layouts.

## getDefaultCategoryLayout

The `getDefaultCategoryLayout` method returns the CMS page entity created by Shopware that represents the default category layout, or null if it doesn't exist.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $cmsPage = $this->helper->Cms()->getDefaultCategoryLayout(); // [!code focus]
    }
}
```
