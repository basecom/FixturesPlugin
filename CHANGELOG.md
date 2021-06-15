# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

[1.1.0]: https://gitlab.com/basecom-gmbh/shopware/v6/plugins/FixturePlugin/-/compare/1.0.0...1.1.0
