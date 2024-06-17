# Installation

There are multiple ways to install the plugin. The recommended method is to use [Composer](https://getcomposer.org/) for the installation.

## Install via composer <Badge type="tip" text="Recommended" />

You can install the plugin via Composer by requiring it. This method follows all of Shopware's best practices for easy installation:

```shell:no-line-numbers
composer require basecom/sw6-fixtures-plugin
```

After installing the plugin via Composer, you'll need to instruct Shopware to load the plugin. To do this, refresh the plugin table and then install and activate the plugin with the following commands (in your Shopware instance):

```shell:no-line-numbers
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

:::warning NEXT STEPS
After the installation, you'll need to modify the service container configuration. See [Tag the fixtures](#tag-the-fixtures) for the next steps.
:::

## Install via ZIP file

Alternatively, you can install the package via a ZIP file. GitHub automatically provides downloads for each tagged version. You can find all downloads on the [Github Releases Page](https://github.com/basecom/FixturesPlugin/releases).

After downloading the ZIP file, either upload it via the Admin area (Admin > Extensions) or place the contents of the ZIP file in the `custom/plugins` directory of your Shopware installation.

Once the plugin is installed, you'll need to instruct Shopware to load the plugin. To do this, refresh the plugin table and then install and activate the plugin. This can be done either through the admin extensions page or by using the following commands (in your Shopware instance):

```shell:no-line-numbers
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

:::warning NEXT STEPS
After the installation, you'll need to modify the service container configuration. See [Tag the fixtures](#tag-the-fixtures) for the next steps.
:::

## Tag the fixtures
The FixturePlugin automatically finds all fixtures within your project using [Symfony Container Tags](https://symfony.com/doc/current/service_container/tags.html). This means that each fixture you write must be tagged within the service container.

### Tag all fixtures automatically <Badge type="tip" text="Recommended" />
The recommended approach is to configure Symfony to automatically tag all classes that extend the `Fixture` class. To do this, navigate to your `services.yaml` file (either within your specific plugin/bundle or the shop itself) and add the following lines:

```yaml
services:
    _instanceof:
        Basecom\FixturePlugin\Fixture:
            tags: ['basecom.fixture']
```

This configuration will automatically detect any class that inherits from the `Basecom\FixturePlugin\Fixture` class and add the `basecom.fixture` tag. For more detailed information, refer to the [Symfony Documentation](https://symfony.com/doc/current/service_container/tags.html).

### Tag each fixture individually
Alternatively, you can tag each fixture class individually. The steps are similar to the automatic tagging method. After you [create your fixture](/writing/first-fixture), you'll need to add the fixture to your `services.yaml` file (either within your specific plugin/bundle or the shop itself) and add the `basecom.fixture` tag to it:

```yaml
services:
    Your\Full\Namespace\Fixtures\CustomerFixture:
        tags: ['basecom.fixture']
```

## Use a specific or non-stable version

### Specific version
If you want to install a specific version, you'll need to provide the version number. Refer to the [CHANGELOG](https://github.com/basecom/FixturesPlugin/blob/main/CHANGELOG.md) for a list of all versions.

With Composer, you can specify the version either in the `composer.json` file or directly via the install command:

```shell:no-line-numbers
# This will install version 2:
composer require basecom/sw6-fixtures-plugin:^2.0

# This will install version 2.2.1
composer require basecom/sw6-fixtures-plugin:2.2.1
```

For the ZIP download, simply choose the ZIP file for the desired version.

### In-development version
If you want to install the in-development version, you can install the `main` branch version. To do this, specify the version `dev-main` in the Composer command:

```shell:no-line-numbers
composer require basecom/sw6-fixtures-plugin:dev-main
```

:::danger
The in-development version may contain undocumented breaking changes or bugs! Please use with caution.
:::

## Next Steps
Here are some more useful resources to help you continue:
- [A detailed explanation about how to write fixtures](/writing/first-fixture)
- [All of our helper methods to make your life easier](/writing/fixture-helper)
- [A lot of example fixture to help you get started](/examples)

Happy Coding!