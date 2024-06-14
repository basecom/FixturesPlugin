# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- Added support for PHP 8.3
- Added support for Shopware 6.6
- Added `--dry` option to all fixture load commands
    - This option will prevent the fixtures from being executed but still prints all fixtures it would execute

### Changed
- Changed argument type on `SalesChannelUtils::getTax()` from `int` to `float`
- **Breaking** By default no fixtures in the vendor directory are loaded. Added option `--vendor` to load them
- Refactored `FixtureTrait` to not use command anymore but direct Fixture Loader
  - `FixtureTrait::loadFixtures` now takes in a FixtureOption parameter
  - `FixtureTrait::runSpecificFixtures` is an alias to run specific fixtures with optionally dependencies
  - `FixtureTrait::runSingleFixture` (before `FixtureTrait::runSingleFixtureWithDependencies`) with dependencies can now be configured as the second parameter
  - `FixtureTrait::runFixtureGroup` is a new function to execute whole fixture groups with optionally dependencies

### Removed
- Dropped support for PHP 8.1
- Dropped support for Shopware 6.3 & 6.4
- Removed FixtureBag

## [2.4.0] - 2023-11-15
### Added
- Added new helper to get locale by iso code

## [2.3.0] - 2023-08-25
### Added
- Added new helper method to load a TaxEntity by its value.
- Added new helper methods to get headless and product comparison sales channels

## [2.2.1] - 2023-05-26
### Fixed
- Restored compatibility for Shopware 6.4 by removing typehints for **EntityRepository** in util classes.

## [2.2.0] - 2023-05-09
### Added
- Added tests for PHP 8.2

### Changed
- Switched from **EntityRepositoryInterface** to **EntityRepository** in util classes to support Shopware 6.5

## Removed
- Dropped support for PHP 7.4 and 8.0

## [2.1.0] - 2022-09-19
### Added
- Added the option `--with-dependencies` / `-w` to the `fixture:run:single` command
   - This command recursively runs all fixtures that are required by the given fixture
- Added new method to the fixture trait: `runSingleFixtureWithDependencies()`

## [2.0.0] - 2022-08-04
> **Please see the UPGRADE.md guide for instructions**

### Added
- Added new helper method `Category()->getByName()`
- Added new helper method `Media()->upload()`
- Added examples in the repository. See the `_examples` folder

### Changed
- The helper methods in the `FixtureHelper` service are now split into multiple smaller units
  - `Media` now holds all media-related helpers
  - `Category` now holds all category-related helpers
  - `SalesChannel` now holds all sales-channel-related helpers
  - `Customer` now holds all customer-related helpers
  - `Cms` now holds all CMS-page-related helpers
  - `PaymentMethod` now holds all payment-method-related helpers
  - `ShippingMethod` now holds all shipping-method-related helpers
- This is the first licensed version. See LICENSE file

## [1.8.0] - 2022-06-03
### Added
- Added FixtureTrait

## [1.7.0] - 2022-05-20
### Added
- Added more helper functions:
  - `getCatalogueRootCategoryId`

### Fixed
- Fixed helper `getDefaultCategoryLayoutId`: It wasn't working in newer shopware versions, because shopware changed the labeling

## [1.6.0] - 2022-05-09
### Added
- Added more helper functions:
  - `getMediaDefaultFolderId`

## [1.5.0] - 2022-04-20
### Added
- Added more helper functions:
  - `get19TaxId`
  - `getStorefrontSalesChannel`

### Changed
- Update to new template version, including own dockware setup


## [1.4.0] - 2021-12-10
### Added
- Added more load options in FixtureLoader:
    - `runFixtureGroup`
    - `runSingle`
- Added the following private, helper function in FixtureLoader:
    - `checkDependenciesAreInSameGroup`
- Added two additional commands to load fixtures:
    - `LoadFixtureGroupCommand`
    - `LoadSingleFixtureCommand`
- Added the follwoing method to abstract Fixture class:
    - `groups()`

### Changed
- Refactored the following helper functions
    - `runAll`

## [1.3.0] - 2021-10-28
### Added
- Added more helper functions:
  - `getLanguageId`
  - `getCountryId`
  - `getSnippetSetId`

### Changed
- Refactored following helper function:
  - `getGermanCountryId`
  - `getDeSnippetSetId`
  - `getGermanLanguageId`

## [1.2.0] - 2021-06-15
### Added
- Support for Shopware 6.4

## [1.1.0] - 2021-04-16
### Added
- Added more helper functions:
  - `getFirstCategoryId`
  - `getFirstShippingMethodId`
  - `getDeSnippetSetId`
  - `getGermanLanguageId`
  - `getDefaultCategoryLayoutId`
- Optimize helper queries to only fetch one result

## 1.0.0 - 2021-04-06
### Added
- Initial version
- Add command to load all fixtures
- Add FixtureHelper with following methods:
  - `getEuroCurrencyId`
  - `getInvoicePaymentMethodId`
  - `getNotSpecifiedSalutationId`
  - `getGermanCountryId`

[2.4.0]: https://github.com/basecom/FixturesPlugin/compare/2.3.0...2.4.0
[2.3.0]: https://github.com/basecom/FixturesPlugin/compare/2.2.1...2.3.0
[2.2.1]: https://github.com/basecom/FixturesPlugin/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/basecom/FixturesPlugin/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/basecom/FixturesPlugin/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/basecom/FixturesPlugin/compare/1.8.0...2.0.0
[1.8.0]: https://github.com/basecom/FixturesPlugin/compare/1.7.0...1.8.0
[1.7.0]: https://github.com/basecom/FixturesPlugin/compare/1.6.0...1.7.0
[1.6.0]: https://github.com/basecom/FixturesPlugin/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/basecom/FixturesPlugin/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/basecom/FixturesPlugin/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/basecom/FixturesPlugin/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/basecom/FixturesPlugin/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/basecom/FixturesPlugin/compare/1.0.0...1.1.0
