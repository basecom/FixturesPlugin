# Upgrade

## v2.x -> v3.x
### See changelog
First have a look at the changes in the CHANGELOG.md

### Support for older versions (Impact: High)
Release V3 dropped support for PHP8.1 and Shopware 6.3 & 6.4.
Now supported are PHP8.2, PHP8.3 and Shopware 6.5 and 6.6

### Dropped FixtureBag (Impact: High)
We have dropped support for the FixtureBag which was given as a parameter in every `load` method. 
This means every Fixture needs to be updated:

#### Before
```php
class CustomerFixture extends Fixture
{
    public function load(FixtureBag $bag): void 
    {
        // ...
    }
}
```

#### After
```php
class CustomerFixture extends Fixture
{
    public function load(): void 
    {
        // ...
    }
}
```

### Vendor Fixtures (Impact: Low)
In Version 2 fixtures within the `vendor` folder run aswell, as project-specific fixtures. This behaviour has changed
in V3. Now only direct fixtures will be executed (that are no within the `vendor` folder).

Every fixture command now supports an additional flag `--vendor` to load vendor fixtures aswell:
```shell
bin/console fixture:load --vendor
bin/console fixture:load:single --vendor MyFixture
bin/console fixture:load:group --vendor MyGroup
```

### Fixture Loader (Impact: Low)
All fixture are loaded from a `FixtureLoader` service. If you never directly accessed the loader and only used
the build in trait and commands, you can ignore this section.

We have completly rewrote the logic to define which fixtures are running. The fixture loader now takes in one
argument: `$options`. Within this options object you can specify exactly how the fixture plugin loads fixtures:

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

All of the options are combinable. The internal commands and traits use this options object aswell.

### FixtureTrait
The `FixtureTrait` used for testing was rewritten to use the new FixtureOptions structure. 

The `runFixtures` method which previously took an array of fixture names, now takes a `FixtureOption` class.
To get the original logic back, you can either provide a `FixtureOption` class with the `$fixtureNames` parameter
filled out or use our new alias method: `runSpecificFixtures` which works like the previous `runFixtures`.
The new method has a parameter aswell to load all dependencies of those fixtures aswell.

```php
// Either:
$this->runFixtures(new FixtureOption(fixtureNames: ['MyFixture', 'AnotherFixture']));

// Or:
$this->runSpecificFixtures(['MyFixture', 'AnotherFixture']);
```

The `runSingleFixtureWithDependencies` was dropped and replaced with `runSingleFixture`. The first argument takes
the name of the fixture and the second one takes a bool to determine if dependencies should be loaded.

```php
// Before:
$this->runSingleFixtureWithDependencies('MyFixture');

// After:
$this->runSingleFixture('MyFixture', true);
```

### Helper methods moved / deleted
We updated a lot of our helper methods. Below you find any method which is affected. All not-mentioned helpers are still
working like V2:

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


### Recommendation: Fixture Helper is now given to any fixture
While not strictly a breaking change, but a recommendation: In V3 every fixtures has access to the fixture helper
by default using `$this->helper`. So our recommandation is to not load the fixture helper manually within dependency
injection and instead use the given helper.


## v1.x -> v2.x
### See changelog
First have a look at the changes in the CHANGELOG.md

### Helper methods have been split
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

**Info:** We haven't removed any helper methods in this release. We only moved them!