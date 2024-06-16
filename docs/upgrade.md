# Upgrade Guide

[[toc]]

## Upgrading from v2 to v3
:::info SEE CHANGELOG
For a full breakdown of all the changes, please refer to the [CHANGELOG](#todo). In this document are only the breaking changes listed.
:::

### Dropped support for older versions <Badge type="danger">Impact: High</Badge>
Release V3 has dropped support for PHP 8.1 and Shopware 6.3 & 6.4. The supported versions are now PHP 8.2, PHP 8.3, and Shopware 6.5 and 6.6.

See the [Supported versions](#todo) page for all supported shopware & php versions.

### Removed FixtureBag <Badge type="danger">Impact: High</Badge>
Support for the FixtureBag parameter in every `load` method has been removed. Each fixture needs to be updated accordingly:

```php::no-line-numbers
class CustomerFixture extends Fixture
{
    public function load(FixtureBag $bag): void  // [!code --]
    public function load(): void  // [!code ++]
    {
        // ...
    }
}
```

### Moved & Deleted some helper methods <Badge type="danger">Impact: High</Badge>
Many of our helper methods have been updated. Below is a list of affected methods. All not mentioned helpers still work like in V2:

- `$this->helper->Category()->getFirst()` is removed. No replacement is available
- `$this->helper->Category()->getByName()` is removed. No replacement is available
- `$this->helper->Customer()->getNotSpecifiedSalutation()` has moved to `$this->helper->Salutation()->getNotSpecifiedSalutation()`
- `$this->helper->SalesChannel()->getCurrencyEuro()` has moved to `$this->helper->Currency()->getCurrencyEuro()`
- `$this->helper->SalesChannel()->getLanguage()` has moved to `$this->helper->LanguageAndLocale()->getLanguage()`
- `$this->helper->SalesChannel()->getLocale()` has moved to `$this->helper->LanguageAndLocale()->getLocale()`
- `$this->helper->SalesChannel()->getCountry()` has moved to `$this->helper->LanguageAndLocale()->getCountry()`
- `$this->helper->SalesChannel()->getSnippetSet()` has moved to `$this->helper->LanguageAndLocale()->getSnippetSet()`
- `$this->helper->SalesChannel()->getTax19()` has moved to `$this->helper->Tax()->getTax19()`
- `$this->helper->SalesChannel()->getTax()` has moved to `$this->helper->Tax()->getTax()`

### Changed methods on the FixtureTrait <Badge type="warning">Impact: Medium</Badge>
The `FixtureTrait` used for testing has been rewritten to use the new `FixtureOption` structure.

The `runFixtures` method, which previously took an array of fixture names, now takes a `FixtureOption` class. To achieve the original behavior, you can either provide a `FixtureOption` class with the `$fixtureNames` parameter filled out or use our new alias method: `runSpecificFixtures`, which works like the previous `runFixtures`. The new method also includes a parameter to load all dependencies of those fixtures.

```php:no-line-numbers
// Before:
$this->runFixtures(['MyFixture', 'AnotherFixture']); // [!code --]

// After:
$this->runSpecificFixtures(['MyFixture', 'AnotherFixture']); // [!code ++]
$this->runFixtures(new FixtureOption(fixtureNames: ['MyFixture', 'AnotherFixture'])); // [!code ++]
```

The `runSingleFixtureWithDependencies` method has been replaced with `runSingleFixture`. The first argument is the name of the fixture, and the second argument is a boolean to determine if dependencies should be loaded.

```php:no-line-numbers
// Before:
$this->runSingleFixtureWithDependencies('MyFixture'); // [!code --]

// After:
$this->runSingleFixture('MyFixture', true); // [!code ++]
```

### Vendor Fixtures don't run by default anymore <Badge type="info">Impact: Low</Badge>
In version 2, fixtures within the `vendor` folder were executed alongside project-specific fixtures. This behavior has changed in V3. Now, only direct fixtures (those not within the `vendor` folder) will be executed.

Every fixture command now supports an additional flag `--vendor` to include vendor fixtures:
```shell:no-line-numbers
bin/console fixture:load --vendor
bin/console fixture:load:single --vendor MyFixture
bin/console fixture:load:group --vendor MyGroup
```

### Rewrote the fixture loader  <Badge type="info">Impact: Low</Badge>
All fixture are loaded from a `FixtureLoader` service. If you never directly accessed the loader and only used the built-in trait and commands, you can ignore this section.

We have completely rewritten the logic to define which fixtures are executed. The fixture loader now accepts a single argument: `$options`. Within this options object, you can specify exactly how the fixture plugin loads fixtures:

```php
readonly class FixtureOption
{
    public function __construct(
        public bool $dryMode = false,
        public ?string $groupName = null,
        public array $fixtureNames = [],
        public bool $withDependencies = false,
        public bool $withVendor = false,
    ) {
    }
}
```

All options are combinable, and the internal commands and traits also use this options object.


### Fixture Helper is now given to any fixture <Badge type="tip">Recommendation</Badge>
In V3, every fixture has access to the fixture helper by default using $this->helper.
Therefore, while not strictly a breaking change, we advise against manually loading the fixture helper via dependency injection and instead using the provided helper.

```php:no-line-numbers
<?php

class MyFixture extends Fixture {
    public function __construct( // [!code --]
        private readonly FixtureHelper $helper // [!code --]
    ) {} // [!code --]

    public function load(): void {
        $this->helper->doSomething();
    }
}
```


## Upgrading from v1 to v2
:::info SEE CHANGELOG
For a full breakdown of all the changes, please refer to the [CHANGELOG](#todo). In this document are only the breaking changes listed.
:::

### Helper methods have been split <Badge type="danger">Impact: High</Badge>
Instead of calling the helper methods like `$helper->getInvoicePaymentMethod()`, you now need to call the
sub util class: `$helper->PaymentMethod()->getInvoicePaymentMethod()`.

The following util classes have been added:
```php
$fixtureHelper->Media()
$fixtureHelper->Category()
$fixtureHelper->SalesChannel()
$fixtureHelper->Customer()
$fixtureHelper->Cms()
$fixtureHelper->PaymentMethod()
$fixtureHelper->ShippingMethod()
```

:::info
We haven't removed any helper methods in this release. We only moved them!
:::
