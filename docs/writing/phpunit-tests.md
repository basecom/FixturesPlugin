# PHPUnit & Tests

In addition to running fixtures via [console command](#todo), you can also execute fixtures within your PHPUnit tests. For this we provide a trait `FixtureTrait` which has all the needed methods to run fixtures.

## Run specific fixtures
To run specific fixtures, simply add the trait to your test and call the `runSpecificFixtures` method:

```php
use Basecom\FixturePlugin\FixtureTrait; // [!code focus]

class MyTest extends TestCase {
    use FixtureTrait; // [!code focus]

    public function testThatSomethingWorks(): void {
        $this->runSpecificFixtures(['CustomerFixture', 'ProductFixture']); // [!code focus]
    }
}
```

Optionally you can also specify that all [dependencies](#todo) of the given fixtures will be loaded aswell by setting the second argument to `true`:

```php
use Basecom\FixturePlugin\FixtureTrait;

class MyTest extends TestCase {
    use FixtureTrait; 

    public function testThatSomethingWorks(): void {
        $this->runSpecificFixtures(['CustomerFixture', 'ProductFixture']); // [!code --]
        $this->runSpecificFixtures(['CustomerFixture', 'ProductFixture'], true); // [!code ++]
    }
}
```

## Run a single fixture
To run a single fixture, simply add the trait to your test and call the `runSingleFixture` method:

```php
use Basecom\FixturePlugin\FixtureTrait; // [!code focus]

class MyTest extends TestCase {
    use FixtureTrait; // [!code focus]

    public function testThatSomethingWorks(): void {
        $this->runSingleFixture('CustomerFixture'); // [!code focus]
    }
}
```

Optionally you can also specify that all [dependencies](#todo) of the given fixture will be loaded aswell by setting the second argument to `true`:

```php
use Basecom\FixturePlugin\FixtureTrait;

class MyTest extends TestCase {
    use FixtureTrait; 

    public function testThatSomethingWorks(): void {
        $this->runSingleFixture('CustomerFixture'); // [!code --]
        $this->runSingleFixture('CustomerFixture', true); // [!code ++]
    }
}
```

## Run a fixture group
To run a whole fixture groups, simply add the trait to your test and call the `runFixtureGroup` method:

```php
use Basecom\FixturePlugin\FixtureTrait; // [!code focus]

class MyTest extends TestCase {
    use FixtureTrait; // [!code focus]

    public function testThatSomethingWorks(): void {
        $this->runFixtureGroup('PDP'); // [!code focus]
    }
}
```

Optionally you can also specify that all [dependencies](#todo) of the given fixture group will be loaded aswell by setting the second argument to `true`:

```php
use Basecom\FixturePlugin\FixtureTrait;

class MyTest extends TestCase {
    use FixtureTrait; 

    public function testThatSomethingWorks(): void {
        $this->runFixtureGroup('PDP'); // [!code --]
        $this->runFixtureGroup('PDP', true); // [!code ++]
    }
}
```

## More complex scenarios
If you want or need more fine-control over which fixtures run you can use the `runFixtures` method. This methods takes a `FixtureOption` parameter
which can be freely configured:

```php
use Basecom\FixturePlugin\FixtureTrait; // [!code focus]
use Basecom\FixturePlugin\FixtureOption; // [!code focus]

class MyTest extends TestCase {
    use FixtureTrait; // [!code focus]

    public function testThatSomethingWorks(): void {
        $this->runFixtures(new FixtureOption( // [!code focus]
            groupName: 'PDP', // [!code focus]
            fixtureNames: ['CategoryFixture', 'AnotherFixture'], // [!code focus]
            withDependencies: true, // [!code focus]
            withVendor: true // [!code focus]
        )); // [!code focus]
    }
}
```

All these parameters are combinable and allow for a very specific execution of fixtures. All other methods are simply alias methods for
this one method with preconfigured options.