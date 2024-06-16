# Installation

There are multiple ways to install the plugin. The recommended way is to use [Composer](#todo) for the installation.

## Install via composer <Badge type="tip" text="Recommended" />

The plugin can be installed via composer by requiring it. It follow all of the shopware best-practices to be easly installable:

```shell:no-line-numbers
composer require basecom/sw6-fixtures-plugin
```

After the plugin is installed via composer, you'll need to tell shopware to load the plugin. For that we need to refresh the plugin table and install/activate it afterwards. This can be done with following commands (in your shopware instance): 

```shell:no-line-numbers
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

:::warning NEXT STEPS
After the installation, you'll need to modify the service container configuration. See [Tag the fixtures](#todo) for the next steps.
:::

## Install via ZIP file

Alternatively you can install the package via ZIP file. Github automatically provides downloads for each tagged version. You can find all downloads on the [Github Releases Page](#todo).

After you download the ZIP file, either upload it via the Admin area (Admin > Extensions) or put the contents of the ZIP file within your `custom/plugins` directory on your shopware installation.

After the plugin is installed, you'll need to tell shopware to load the plugin. For that we need to refresh the plugin table and install/activate it afterwards. This can be done either in the admin extensions page or with following commands (in your shopware instance):

```shell:no-line-numbers
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

:::warning NEXT STEPS
After the installation, you'll need to modify the service container configuration. See [Tag the fixtures](#todo) for the next steps.
:::

## Tag the fixtures
The fixture plugin automatically finds all fixtures within your project. For this we are using [Symfony Container Tags](#todo). That means each fixture you write must be tagged within the service container.

### Tag all fixtures automatically <Badge type="tip" text="Recommended" />
The recommended way is to tell Symfony to tag all classes which extend the `Fixture` class automatically. For this navigate to your `services.yaml` (either in your specific plugin / bundle or the shop itself) and add the following lines to it:

```yaml
services:
    _instanceof:
        Basecom\FixturePlugin\Fixture:
            tags: ['basecom.fixture']
```

This will detect any class that inherits the `Basecom\FixturePlugin\Fixture` class and add the `basecom.fixture` tag automatically. See the [Symfony Documentation](#todo) for more in-depth information.

### Tag each fixture individually
Alternatively you can also tag each fixture class individually. In this case the steps are quite similar. After you [create your fixture](#todo), you'll need to add the Fixture to your `services.yaml` file (either in your specific plugin / bundle or the shop itself) and add the tag `basecom.fixture` to it:

```yaml
services:
    Your\Full\Namespace\Fixtures\CustomerFixture:
        tags: ['basecom.fixture']
```

## Use a specific or non-stable version

### Specific version
If you want to install a specific version, you'll need to provide the version number. See the [CHANGELOG](#todo) for all versions.

In composer you can just specify the version in the `composer.json` file or via the install command:

```shell:no-line-numbers
# This will install version 2:
composer require basecom/sw6-fixtures-plugin:^2.0

# This will install version 2.2.1
composer require basecom/sw6-fixtures-plugin:2.2.1
```

For the zip download just choose the ZIP file for the correct version.

### In-development version
If you want to install the in-development version, you can install the `main` branch version. For this you'll need to specify the version `dev-main` for the composer command:

```shell:no-line-numbers
composer require basecom/sw6-fixtures-plugin:dev-main
```

:::danger
The in-development version can contain non-documentated breaking changes or bugs! Please use with caution!
:::

## Next Steps
Here are more useful resources:
- [A detailed explanation about how to write fixtures](#todo)
- [All of our helper methods to make your life easier](#todo)
- [A lot of example fixture to help you get started](#todo)

Happy Coding!