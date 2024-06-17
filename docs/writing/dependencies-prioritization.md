# Dependencies & Prioritization

## Dependencies between Fixtures
Each fixture can optionally define dependencies on other fixtures. A common example is creating customer groups before customers or properties before products.

Dependencies can be specified by overriding the `dependsOn` method in the fixture itself.

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function dependsOn(): array { // [!code ++]
        return [ // [!code ++]
            CustomerGroupFixture::class, // [!code ++]
            SalutationFixture::class, // [!code ++]
        ] // [!code ++]
    } // [!code ++]
}
```

When executing fixtures, they will automatically be sorted by dependencies. This means the plugin will ensure that all dependencies run first. If there is a [circular dependency](https://en.wikipedia.org/wiki/Circular_dependency) — meaning fixtures require each other — the plugin will throw an error.

Fixtures can have multiple dependencies, and the plugin will also handle dependencies of dependencies.

### Executing single fixtures with dependencies
Sometimes you may wish to execute only a single fixture. When you run the [command](/writing/available-commands#run-a-specific-fixture) to execute a single fixture, it will **not** run any dependency fixtures — only the one specified.

```shell:no-line-numbers
bin/console fixture:load:single CustomerFixture
```

To also include all dependencies (recursively), you can pass the `--with-dependencies` or `-w` flag to the command:

```shell:no-line-numbers
bin/console fixture:load:single --with-dependencies CustomerFixture
```

:::tip
If you want to see the order of fixtures being executed without actually running them, you can call any of the fixture commands with the `--dry` argument!
:::

## Prioritization

In addition to dependencies, fixtures can also be assigned a priority. By default, each fixture has a priority of 0. You can assign a custom priority by overriding the `priority` method:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function priority(): int { // [!code ++]
        return 10; // [!code ++]
    } // [!code ++]
}
```

Each fixture will be sorted by priority. Note that dependencies are always considered first, before priority. This means dependencies will always be executed in the correct order. Fixtures are sorted in descending order, meaning fixtures with a higher priority will be executed first.
