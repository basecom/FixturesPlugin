# Fixture Plugin

The FixturePlugin for Shopware 6 offers convenient commands and structures to create and manage fixtures for your shop project or plugin.

## [Documentation](https://basecom.github.io/FixturesPlugin/getting-started.html)

Please see the [official documentation](https://basecom.github.io/FixturesPlugin/getting-started.html) to see how to get started!

## Installation

You can easily install the plugin via Composer in any existing Shopware shop:

```shell
# Install the plugin via Composer
composer require basecom/sw6-fixtures-plugin

# Refresh the plugin list and install/activate the plugin
bin/console plugin:refresh
bin/console plugin:install --activate BasecomFixturePlugin
```

## Contribution
This plugin uses a full-featured Dockware docker image. It already comes with a pre-installed Shopware 6 instance and everything you need to start developing.

Please see the [Dockware documentation](https://dockware.io/docs).

To start developing, simply start the container:
```bash
> docker compose up -d
```

Access the container:
```bash
> make shell
```

Install the dependencies and make everything ready (defined in composer.json and package.json). This command needs to be
executed from the host-system (not in shell)
```bash
> make install
```

### Linting
Before committing, please run the linting and static analysis tools. This command also needs to be executed from the
host machine (not in shell):
```bash
> make lint
```


### Github Actions
The Github actions pipeline is already pre-configured. It contains multiple jobs for all linting, static analysis and testing tools.
