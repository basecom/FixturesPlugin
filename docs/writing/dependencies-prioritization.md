# Dependencies & Prioritization

## Dependencies between Fixtures
Each fixture can optionally define dependencies to other fixtures. One common example is the creation of customer groups before customers. Or properties before products, etc.

Dependencies can be specified by overridding a `dependsOn` method on the fixture itself.

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function dependsOn(): array { // [!code focus]
        return [ // [!code focus]
            CustomerGroupFixture::class, // [!code focus]
            SalutationFixture::class, // [!code focus]
        ] // [!code focus]
    } // [!code focus]
}
```

When executing fixtures they will automatically be sorted by dependencies. Meaning the plugin will ensure that all dependencies will run first. If there is a [circular dependency](#todo), - meaning fixtures require eachother, the plugin will throw an error.

Fixtures can have multiple dependencies and the plugin will also handle dependencies of dependencies.

### Executing single fixtures with dependencies
Sometimes you may wish to execute only one single fixture. When you run the [command](#todo) to run a single fixture it will **not** execute any dependency fixtures, only the one specified.

```shell:no-line-numbers
bin/console fixture:load:single CustomerFixture
```

To also include all dependencies (recursively), you can pass the   
`--with-dependencies` / `-w` flag to the command:

```shell:no-line-numbers
bin/console fixture:load:single --with-dependencies CustomerFixture
```

:::tip
If you want to see the order of fixtures being executed without actually executing them, you can call any of the fixture commands with the `--dry` argument!
:::

## Prioritization

In addition to dependencies fixtures can also be assigned a priority. By default each fixture has priority 0. You can assign a custom priority by overridding the `priority` method:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function priority(): int { // [!code focus]
        return 10; // [!code focus]
    } // [!code focus]
}
```

Each fixture will be sorted by priority. Note that dependencies are always considered first, before priority. Meaning dependencies will always be in the correct order! Fixtures are sorted in decending order, meaning fixtures with a higher priority will be executed first.
