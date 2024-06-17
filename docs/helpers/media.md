# Media Helpers

This helper provides utility methods to work with media assets.

## getDefaultFolder

The `getDefaultFolder` method is originally copied from [shopwares core](https://github.com/shopware/shopware/blob/6.5.x/src/Core/Content/Media/MediaService.php#L132) and made public for use in fixtures.

It searches for the default folder for any given entity (e.g., product) and returns the media folder entity or null if not found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $productFolder = $this->helper->Media()->getDefaultFolder('product'); // [!code focus]
    }
}
```

## upload
This method "uploads" a real file within Shopware. It takes a real file path and uploads it as a complete media entity:

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $folderId = $this->helper->Media()->getDefaultFolder('product')?->getId();
        $this->helper->ensureNotEmpty($folderId);

        $this->helper->Media()->upload( // [!code focus]
            mediaId: '019021d21d9571309bdc48db825032f4', // [!code focus]
            folderId: $folderId, // [!code focus]
            fileName: '/path/to/the/real/file.png', // [!code focus]
            extension: 'png', // [!code focus]
            contentType: 'image/png' // [!code focus]
        ); // [!code focus]
    }
}
```