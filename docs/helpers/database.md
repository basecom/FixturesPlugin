# Database Helpers

The database helpers provide a more generic way of handling the database compared to the other helper methods.

## deleteEntities
The `deleteEntities` method allows a fixture to delete all entities that match a given criteria. It takes the entity name and criteria as parameters and deletes all found entities.

```php
<?php

class MyFixture extends Fixture {
    public function load(): void {
        $this->helper->Database()->deleteEntities( // [!code focus]
            entity: ProductDefinition::ENTITY_NAME, // [!code focus]
            criteria: (new Criteria())->addFilter(new EqualsFilter('name', 'Example')) // [!code focus]
        ); // [!code focus]
    }
}
```

This example would remove all products that have the name "Example."
