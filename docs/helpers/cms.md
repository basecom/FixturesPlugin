# CMS Helpers

This helper provides utility methods to work with cms pages and layouts.

## getDefaultCategoryLayout

The `getDefaultCategoryLayout` method returns the cms page entity, which is created by shopware and represents the default category layout or null if it doesn't exists.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $cmsPage = $this->helper->Cms()->getDefaultCategoryLayout(); // [!code focus]
    }
}
```
