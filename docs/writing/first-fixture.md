# Your first fixture

Each fixture must extend the abstract `Basecom\FixturePlugin\Fixture` class. It has one abstract method which needs to be implemented: `load` and a few optional methods. See the next chapters for more details on them.

Let's begin by creating a simple fixture together to create a new tax rate (90%). Fixtures can either be part of another Plugin, Bundle or in the shop itself.

For the sake of the tutorial I assume you already have a plugin or theme for your project. If not, please follow the [offical Shopware documentation](#todo) to create a new plugin.

## Empty fixture

After you've created your plugin, we can begin to implement the fixture. For this create a new directory within the `src` directory of your plugin called: `Fixtures`.

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

If you followed the [Installation instructions] you'll already have a `services.yaml` file configured. If not, revisit the installation page and add the needed service definition.

After creating the fixture, we can execute it using the following command:
```shell:no-line-numbers
bin/console fixture:load
```

It should print the just created fixture and run successfully. Of course the fixture does not do anything at the moment.

:::tip
Fixtures are bascially just normal classes. You can execute whatever logic you want within the `load` method of your fixture. That means you can interact with repositories to create or manipulate entites, run importer scripts or even create media files on the fly. See all of [our examples](#todo) to get more ideas of how to use Fixtures!
:::

## Create a simple entity

To create a new tax rate, we can use the `$taxRepository` from shopware. Fixture are normal [Symfony services](#todo), so we can use Dependency Injection to get an instance of the repository:

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

Awesome! Now we can use the [default shopware logic](#todo) to create our entity:

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

After successfully executing the command, you now should have an additional tax rate of 90%!

## Better use upsert instead of create
We have one problem with our fixtures. Try to run the fixtures again:

```shell:no-line-numbers
bin/console fixture:load
```

We now have two tax entities with each a rate of 90%. Meaning everytime we execute the fixtures, we get create a fully new tax entity without cleaning the old one!

We now have multiple possibilities of handling that. We could, for example, remove the old tax rate and then create a new. Or we could first check if it exists and then update it instead. Fortunately shopware already has a build in method to handling these kind of cases: [upsert](#todo)!

`upsert` requires a fixed ID. Then it will check if an entity with the same ID exists and update it. If it does not yet exists it will create a new entity with the ID.

If we apply this our fixture new will look something like this:

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
To get a random UUID which can be used for these cases, you can use the    
`bin/console fixture:uuid` command which just prints a random UUID in the console.
:::

If we now manually remove the old tax rates and execute the fixtures multiple times again, we only have on additional tax rate of 90% :tada:

```shell:no-line-numbers
bin/console fixture:load
bin/console fixture:load
```

## Next Steps
Congratulations! You just wrote your first fixture :tada: 

Here are more useful resources:
- [See dependencies & prioritization to even better manage your fixtures](#todo)
- [All of our helper methods to make your life easier](#todo)
- [A lot of example fixture to help you get started](#todo)
