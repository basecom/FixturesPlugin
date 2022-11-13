# Fixture Plugin

The fixture plugin is really helpful if you want to create some static demo data for your shopware instance.

## Installation

Just add it to your project via composer:

```shell
composer require basecom/sw6-fixtures-plugin
```

Afterwards you can install the plugin, like any other Shopware Plugin using the administration or console command:

```shell
./bin/console plugin:install --activate BasecomFixturePlugin
```


## Create Fixtures

### Basic Fixture
Create a new file in the specific folder of your project for the fixtures and extend this file from abstract class "**Fixture**" in the library.

Then just implement the "**load()**" method with fixture logic.

```php 
class CustomerFixture extends Fixture
{
    /**
     * @param FixtureBag $bag
     * @return void
     */
    public function load(FixtureBag $bag): void
    {
        // custom code 
    }
}
```

### Priority and Dependency
You can also add an optional priority or dependency of other Fixture classes via the corresponding "**dependsOn()**" or "**priority()**" method.

```php 
public function priority(): int
{
    return 0;
}
```


### Groups
If you want to run specific fixtures as a group later, implement the "**groups()**" method from the abstact class and return an array of strings as group names.

```php 
/** @return string[] */
public function groups(): array
{
    return [];
}
```


## Register Fixtures
If you're using service autowiring, then nothing needs to be done to register your fixtures.
If you're not using autowiring, then please register your fixtures with the tag `basecom.fixture`.


```xml
<service id="MyNamespace\Fixtures\Customer\CustomerFixture">
    <argument type="service" id="Basecom\FixturePlugin\FixtureHelper"/>
    <argument type="service" id="customer.repository"/>
    <tag name="basecom.fixture"/>
</service>
```


## Running Fixtures

### Run all fixtures
To run all registered fixtures, just use this command.
```bash
bin/console fixture:load
```

### Run single fixture
To run a single fixture, use this command with your fixture name as parameter.

```bash
bin/console fixture:load:single <name>

# fixture class is named "DummyFixture.php", (it´s case-insensitive)
bin/console fixture:load:single dummyFixture
```

By default, if you run a single fixture it will ignore all its dependencies. If you want to run the single fixture, 
including all dependencies recursively, use the `--with-dependencies` option.

```bash
bin/console fixture:load:single --with-dependencies <name>

# fixture class is named "DummyFixture.php", (it´s case-insensitive)
bin/console fixture:load:single --with-dependencies dummyFixture
```

### Run group
To run a group of fixture, run this command with group name as parameter (specified via **groups()** method). It´s also case-insensitive.

```bash
bin/console fixture:load:group <name>
```


## Best Practices

### Plugin Development
When you want to add fixtures to a plugin that you build, you might not want to deliver these fixtures in a production version of your plugin.
In this case, we only want to add it to the "development" or "testing" scope of our plugin.

Create a new folder for your fixtures in a folder that is not your source folder that is delivered.
You could use your "tests" folder for instance.

We then use the Shopware `build()` function of our main `Plugin` class to add the code below.
This code will verify if our DEV dependencies from our composer dependencies are installed (just use any check like, is PHPUnit existing?...).
Once it recognizes that DEV requirements are installed, we verify that our fixtures directory also exists, and then simply load
those files with our correct namespace in our class loader.
Afterwards we also load our custom XML services for our fixtures.

With this approach, the `bin/console` command of this FixturePlugin will only find fixtures, if dev-dependencies are installed in our plugin that we develop.
If only production dependencies are installed, nothing is found and therefore no fixtures are (accidentally) loaded.

```php 
# use any of your dev-dependencies as "indicator"
$composerDevReqsInstalled = file_exists(__DIR__ . '/../../vendor/bin/phpunit');

if ($composerDevReqsInstalled) {

    $dirFixtures = __DIR__ . '/../../tests/Fixtures';

    if (is_dir($dirFixtures)) {
        $classLoader = new ClassLoader();
        $classLoader->addPsr4("MyNamespace\\Fixtures\\", $dirFixtures, true);
        $classLoader->register();

        $loader->load('services/fixtures/fixtures.xml');
    }
}
```




## Contribution
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


### Github Actions
The Github actions pipeline is already pre-configured. It contains multiple jobs for all linting, static analysis and testing tools.
