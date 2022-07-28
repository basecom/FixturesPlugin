# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

[1.8.0]: https://github.com/basecom/FixturesPlugin/compare/1.7.0...1.8.0
[1.7.0]: https://github.com/basecom/FixturesPlugin/compare/1.6.0...1.7.0
[1.6.0]: https://github.com/basecom/FixturesPlugin/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/basecom/FixturesPlugin/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/basecom/FixturesPlugin/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/basecom/FixturesPlugin/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/basecom/FixturesPlugin/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/basecom/FixturesPlugin/compare/1.0.0...1.1.0
