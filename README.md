# FreeNAS RESTful API (v2) - PHP SDK

This package is built upon the v2.0 of the FreeNAS API.
* [FreeNAS API v2.0 REST documentation](https://api.ixsystems.com/freenas/)

## How to use

```bash
composer require sandwave-io/freenas-php
```

```php
$freenas = new \SandwaveIo\FreeNAS\RestClient('https://my-freenas-install.io/api/v2.0', 'root', 'SuperSecretPassword123');

// This example shows how to create a dataset, and a user that has access rights to that dataset.
$dataset = $freenas->createDataset('store01', 'my-dataset', 20 * 1024**3);
$user    = $freenas->createUser(1001, 'my-user', $dataset->getMountPoint(), 'SuperSecretUserPassword123');
```


## How to contribute

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix). 
* Also make sure PHPStan does not find any bugs.

```bash

vendor/bin/php-cs-fixer fix

vendor/bin/phpstan analyze

vendor/bin/phpunit --coverage-text

```

These tools will also run in GitHub actions on PR's and pushes on master.
