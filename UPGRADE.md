# Upgrade

## v1.x -> v2.x
### See changelog
First have a look at the changes in the CHANGELOG.md

### Helper methods have been split
Instead of calling the helper methods like `$helper->getInvoicePaymentMethod()`, you now need to call the
sub util class: `$helper->PaymentMethod()->getInvoicePaymentMethod()`.

The following util classes have been added:
```php
$fixtureHelper->Media()
$fixtureHelper->Category()
$fixtureHelper->SalesChannel()
$fixtureHelper->Customer()
$fixtureHelper->Cms()
$fixtureHelper->PaymentMethod()
$fixtureHelper->ShippingMethod()
```

**Info:** We haven't removed any helper methods in this release. We only moved them!