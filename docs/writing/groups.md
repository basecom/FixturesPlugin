# Grouping
Fixtures can be assigned to one or more groups. Groups allow fixtures to be executed together without executing any other fixtures outside the group.

## Assign groups to fixtures
To assign one or more groups to any fixture, you need to override the `groups` method in the fixture itself:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function groups(): array { // [!code ++]
        return [ // [!code ++]
            'customers', // [!code ++]
            'unit-tests', // [!code ++]
        ] // [!code ++]
    } // [!code ++]
}
```
Groups don't need to be defined anywhere explicitly. A group will exist as soon as one or more fixtures use its name. By default, fixtures are not assigned to any group.

## Running specific groups
To execute a specific group of fixtures, use the `fixture:load:group` command:

```shell:no-line-numbers
bin/console fixture:load:group customers
```

This command will execute all fixtures that are part of the `customers` group.

## Notice about inter-group dependencies <Badge type="warning">Warning</Badge> 
:::info
Please see the chapter about [Dependencies & Prioritization](/writing/dependencies-prioritization) to learn more about dependencies between fixtures.
:::

When a fixture has dependencies and is part of a group, all dependencies must also be in the same group(s). If any dependency cannot be resolved within the same group, the command will throw an error!

## Use cases
Here are a few use cases that we at [basecom](https://basecom.de) implement within our fixtures. This list is not exhaustive, and if you have more use cases, please let us know so we can add them!

### Groups for specific environments
We often use groups to specify fixtures that should only run in a specific environment. For example, we frequently have a `staging` group. These fixtures will only be executed in the staging environment (as part of our deployment). We use this to configure settings, create demo accounts, and more, making testing simpler.

Sometimes we even have a `production` group. In this group, we put fixtures to create email template types or custom entities. Without the FixturePlugin, this was often done via [Migrations](https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/database-migrations.html) or [plugin lifecycle methods](https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/plugin-lifecycle.html), but we find it quite convenient to have it all in one place.

### Groups for specific features
When developing larger features, you'll often end up with a lot of different fixtures. For example, implementing the Product Detail Page (PDP) might require at least one product, reviews, property groups and options, prices, tax information, and more.

We recommend splitting this into multiple smaller fixtures but assigning them all to the same group, such as `pdp`. This way, if any developer needs to work on the PDP, they can just execute the `pdp` fixtures and have all the necessary data to work on it.

These groups can also be used in [automated tests](/writing/phpunit-tests), so you don't need to load all fixtures when testing the PDP or any other feature.
