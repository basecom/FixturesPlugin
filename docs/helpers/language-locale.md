# Language & Locale Helpers

This helper provides utility methods to work with languages, locales, snippet sets, and countries.

## getLanguage

The `getLanguage` method takes the name of any language as a parameter and returns the language entity that corresponds to that name, or null if none is found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $german = $this->helper->LanguageAndLocale()->getLanguage('Deutsch'); // [!code focus]
    }
}
```

## getLocale

The `getLocale` method takes the ISO code of any locale as a parameter and returns the locale entity that corresponds to that code, or null if none is found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $germanLocale = $this->helper->LanguageAndLocale()->getLocale('de-DE'); // [!code focus]
    }
}
```

## getCountry

The `getCountry` method takes the ISO code of any country as a parameter and returns the country entity that corresponds to that code, or null if none is found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $germany = $this->helper->LanguageAndLocale()->getCountry('DE'); // [!code focus]
    }
}
```

## getSnippetSet

The `getSnippetSet` method takes the ISO code of any locale as a parameter and returns the snippet set entity that is associated with that locale, or null if none is found.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $snippets = $this->helper->LanguageAndLocale()->getSnippetSet('de-DE'); // [!code focus]
    }
}
```