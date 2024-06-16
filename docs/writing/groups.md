# Grouping
Fixtures can be put into one or more groups. Groups allow fixtures to be executed together without executing any other fixtures outside the group.

## Assign groups to fixtures
To assign one or more groups to any fixtures, you'll need to override the `groups` method on the fixture itself:

```php
<?php

namespace MyName\MyPlugin\Fixtures;

use Basecom\FixturePlugin\Fixture;

class CustomerFixture extends Fixture {
    public function load(): void {}

    public function groups(): array { // [!code focus]
        return [ // [!code focus]
            'customers', // [!code focus]
            'unit-tests', // [!code focus]
        ] // [!code focus]
    } // [!code focus]
}
```

Groups don't need to be defined anywhere. Whenever one or more fixtures are using a group name, it will exists. By default fixtures are not assigned in any group.

## Running specific groups
To execute a specific group of fixtures, we can use the `fixture:load:group` command:

```shell:no-line-numbers
bin/console fixture:load:group customers
```

This will execute all fixtures that are within the `customers` group. 

## Notice about inter-group dependencies <Badge type="warning">Warning</Badge> 
:::info
Please see the chapter about [Dependencies & Prioritization](#todo) to learn more about dependencies between fixtures.
:::

Whenever a fixture has dependencies and is part of a group, all dependencies also must be in the same groups. If you have any dependency which can't be resolved within the same group, the command will throw an error!


## Use cases
Here are a few use cases, which we at [basecom](https://basecom.de) use within our fixtures. Of course this is no exclusive list and if have more use-cases please let us know, so we can add them to this list!

### Groups for specific environments
We often use groups to specify fixtures which should only run in a specific environment. For example we often have a `staging` group. These fixtures will only be executed on the staging environment (as part of our deployment). We use this to configure settings, creating demo accounts are more so that testing gets simpler.

Also we sometimes even have a `production` environment. In this environment we put fixtures to create e-mail template types or custom entities. Without the fixture plugin this was often done via [Migrations](#todo) or [plugin lifecycle hooks](#todo), but we found it quite nice to have it all in one place.

### Groups for specific features
When developing some larger features you'll often end up with a lot of different fixtures. One example might be the implementation of the Product Detail Page. For this you'll need at least one product, reviews, property groups and options, prices, tax information and more.

We recommend splitting this up into multiple smaller fixtures but assigning them all to the same group: `pdp`. This way if any developers needs to work on the PDP, they can just execute the pdp fixtures and have all necessary data to work on it.

Also these groups can be used in [automated tests](#todo), so that you don't need to load all fixtures when testing the PDP or any other feature.
