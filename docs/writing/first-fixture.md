---
prev:
  text: 'Upgrade guide'
  link: '/upgrade'
---

# Your first fixture

Each fixture must extend the abstract `Basecom\FixturePlugin\Fixture` class. This class has one abstract method, `load`, which needs to be implemented, along with a few optional methods. See the following chapters for more details on these optional methods.

Let's start by creating a simple fixture that will create a new tax rate (90%). Fixtures can be part of another plugin, bundle, or the shop itself.

For the sake of this tutorial, we assume you already have a plugin or theme for your project. If not, please follow the [offical Shopware documentation](https://developer.shopware.com/docs/guides/plugins/plugins/plugin-base-guide.html) to create a new plugin.

## Empty fixture

After you've created your plugin, we can begin to implement the fixture. Create a new directory within the `src` directory of your plugin called `Fixtures`.

A bare minimum fixture looks like this:
```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class TaxFixture extends Fixture {
    public function load(): void {

    }
}
```

If you followed the [Installation instructions](/installation) you'll already have a `services.yaml` file configured. If not, revisit the installation page and add the necessary service definition.

After creating the fixture, you can execute it using the following command:
```shell:no-line-numbers
bin/console fixture:load
```

This should print the newly created fixture and run it successfully. Of course, the fixture does not do anything at the moment.

:::tip
Fixtures are basically just normal classes. You can execute whatever logic you want within the `load` method of your fixture. This means you can interact with repositories to create or manipulate entities, run importer scripts, or even create media files on the fly. See all of [our examples](/examples/index) to get more ideas on how to use fixtures!
:::

## Create a simple entity

To create a new tax rate, we can use the `$taxRepository` from Shopware. Fixtures are normal [Symfony services](https://symfony.com/doc/current/service_container.html), so we can use Dependency Injection to get an instance of the repository:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository; // [!code ++]

class TaxFixture extends Fixture {
    public function __construct( // [!code ++]
        private readonly EntityRepository $taxRepository // [!code ++]
    ) {} // [!code ++]

    public function load(): void {

    }
}
```

Awesome! Now we can use the [default shopware logic](https://developer.shopware.com/docs/guides/plugins/plugins/framework/data-handling/writing-data.html) to create our entity:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;
use Shopware\Core\Framework\Context;  // [!code ++]
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class TaxFixture extends Fixture {
    public function __construct(
        private readonly EntityRepository $taxRepository
    ) {}

    public function load(): void {
        $this->taxRepository->create( // [!code ++]
            [ // [!code ++]
                'taxRate' => 90, // [!code ++]
                'name' => 'Ultra high tax', // [!code ++]
                'position' => 4, // [!code ++]
            ], // [!code ++]
            Context::createDefaultContext() // [!code ++]
        ); // [!code ++]
    }
}
```

Execute the fixtures again:
```shell:no-line-numbers
bin/console fixture:load
```

After successfully executing the command, you should now have an additional tax rate of 90%!

## Better use upsert instead of create
We have one problem with our fixture. Try to run the fixtures again:

```shell:no-line-numbers
bin/console fixture:load
```

We now have two tax entities, each with a rate of 90%. This means that every time we execute the fixtures, a new tax entity is created without cleaning the old one!

We have multiple ways to handle this situation. For example, we could remove the old tax rate before creating a new one, or check if it exists and then update it. Fortunately, Shopware already has a built-in method for handling these cases: [upsert](https://developer.shopware.com/docs/guides/plugins/plugins/framework/data-handling/writing-data.html#upserting-data)!

The `upsert` method requires a fixed ID. It will check if an entity with the same ID exists and update it. If it does not exist, it will create a new entity with that ID.

Applying this to our fixture, it will look something like this:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class TaxFixture extends Fixture {
    public function __construct(
        private readonly EntityRepository $taxRepository
    ) {}

    public function load(): void {
        $this->taxRepository->create( // [!code --]
        $this->taxRepository->upsert( // [!code ++]
            [
                'id' => '0190210cf21273af9cb04437e6787605',  // [!code ++]
                'taxRate' => 90,
                'name' => 'Ultra high tax',
                'position' => 4,
            ],
            Context::createDefaultContext()
        );
    }
}
```

:::tip
To get a random UUID that can be used for these cases, you can use the `bin/console fixture:uuid` command, which prints a random UUID in the console.
:::

If we now manually remove the old tax rates and execute the fixtures multiple times, we will only have one additional tax rate of 90% :tada:

```shell:no-line-numbers
bin/console fixture:load
bin/console fixture:load
```

## Next Steps
Congratulations! You've written your first fixture :tada: 

Here are some more useful resources to help you continue:
- [See dependencies & prioritization to even better manage your fixtures](/writing/dependencies-prioritization.html)
- [All of our helper methods to make your life easier](/writing/fixture-helper)
- [A lot of example fixture to help you get started](/examples/index)
