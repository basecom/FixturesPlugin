# Fixture Helper

Often in fixtures, you need to access specific entities in the database, delete old records, or create new ones. Writing all these database queries multiple times can become quite cumbersome. To simplify this, we have integrated a handy helper class: `FixtureHelper`.

The `FixtureHelper` provides numerous small methods to fetch data from the database or write records.

:::warning
The fixture helpers are designed to be most convenient for local development and fixtures. They are not intended for use in production code!
:::

## How to use
The `FixtureHelper` is already included in any fixture (since version 3). You can simply access the fixture helper:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {
        $this->helper->SalesChannel()->getStorefrontSalesChannel(); // [!code focus]
    }
}
```

If you are using version 2 or want to use the `FixtureHelper` in other cases (like tests), you can use [dependency injection](https://symfony.com/doc/current/components/dependency_injection.html) like any other [Symfony service](https://symfony.com/doc/current/service_container.html):

```php
<?php

class MyExampleService {
    public function __construct(
        private readonly FixtureHelper $helper // [!code focus]
    ) {}

    public function doSomething() {
        $this->helper->SalesChannel()->getStorefrontSalesChannel(); // [!code focus]
    }
}
```

## Available helpers
Below is a list of all available helper categories. Please refer to the documentation for each category to see all the available methods:

| Helper            | Description                                             | Documentation                      |
| ----------------- | ------------------------------------------------------- | ---------------------------------- |
| Utility Methods   | Some general methods to help writing fixtures           | [Utility Methods](#todo)           |
| Media             | Methods to interact with the media entities             | [Media Helpers](#todo)             |
| Category          | Methods to interact with the categoriy entities         | [Category Helpers](#todo)          |
| Sales Channel     | Methods to interact with the sales channel entities     | [Sales Channel Helpers](#todo)     |
| Salutation        | Methods to interact with the salutation entities        | [Salutation Helpers](#todo)        |
| CMS               | Methods to interact with the cms page entities          | [CMS Helpers](#todo)               |
| Payment Method    | Methods to interact with the payment method entities    | [Payment Method Helpers](#todo)    |
| Shipping Method   | Methods to interact with the shipping method entities   | [Shipping Method Helpers](#todo)   |
| Language & Locale | Methods to interact with the language & locale entities | [Language & Locale Helpers](#todo) |
| Currency          | Methods to interact with the currency entities          | [Currency Helpers](#todo)          |
| Tax               | Methods to interact with the tax entities               | [Tax Helpers](#todo)               |
| Database          | Methods to interact with the database itself            | [Database Helpers](#todo)          |

Each helper category provides specialized methods to make working with different aspects of your Shopware setup easier and more efficient.
