# Media Helpers

This helper provides utility methods to work with media assets.

## getDefaultFolder

The `getDefaultFolder` method is originally copied from [shopwares core](#todo) itself and made public for use in fixtures.

It searches the default folder for any given entity (for example product) and return the media folder entity or null, if not found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $productFolder = $this->helper->Media()->getDefaultFolder('product'); // [!code focus]
    }
}
```

## upload
This methods "uploads" a real file within shopware. It takes a real file path and uploads it as a full media entity:

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