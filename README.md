# Fixture Plugin

The fixture plugin is really helpful if you want to create some static demo data for your shopware instance.

## Installation

Just add it to your project via composer: "**composer require basecom/sw6-fixtures-plugin**" (or add entry in psh dependency).


## Create Fixtures

1. Create a new file in the specific folder of your project for the fixtures.
2. Extend this file from abstract class "**Fixture**" in the library.
3. Implement the "**load()**" method with fixture logic
4. **Optional:** Add a priority or dependency of other Fixture classes via the corresponding "**dependsOn()**" or "**priority()**" method.
5. If you want to run specific fixtures as a group later, implement the "**groups()**" method from the abstact class and return an array of strings as group names.

## Run the fixtures

- To run a single fixture, use the command "**bin/console fixture:load:single <name>" with Fixture name as parameter.
    - E.g. fixture class is named "DummyFixture.php", run **bin/console fixture:load:single dummyFixture (it´s case-insensitive).
- To run a group of fixture, run "**bin/console fixture:load:group <name>**" with group name as parameter (specified via **groups()** method). It´s also case-insensitive.
- To run all fixtures, run "**bin/console fixture:load**".

### Start developing
This template uses a full-featured Dockware docker image. It already comes with a pre-installed Shopware 6 instance and everything you need to start developing.

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


### GitLab pipeline
The GitLab pipeline is already pre-configured. It contains multiple jobs for all linting, static analysis and testing tools.

The pipeline runs all tests for the latest stable Shopware 6 version. But you have several options for the PHPUnit tests:

