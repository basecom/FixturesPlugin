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

