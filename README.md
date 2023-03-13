[![](https://user-images.githubusercontent.com/60096509/91668964-54ecd500-eb11-11ea-9c35-e8f0b20b277a.png)](https://sandwave.io)

# FreeNAS RESTful API (v2) - PHP SDK

![Codecov](https://img.shields.io/codecov/c/github/sandwave-io/freenas-php?style=flat-square)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/sandwave-io/freenas-php/ci.yml?branch=main)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/sandwave-io/freenas?style=flat-square)
![Packagist PHP Version Support](https://img.shields.io/packagist/v/sandwave-io/freenas?style=flat-square)
![Packagist Downloads](https://img.shields.io/packagist/dt/sandwave-io/freenas?style=flat-square)

This package is built upon the v2.0 of the FreeNAS API.
* [FreeNAS API v2.0 REST documentation](https://api.ixsystems.com/freenas/)


## How to use

```bash
composer require sandwave-io/freenas-php
```

```php
$freenas = new \SandwaveIo\FreeNAS\RestClient(
    'https://my-freenas-install.io/api/v2.0/', // base url
    'root',                  // user
    'SuperSecretPassword123' // password
);

// This example shows how to create a dataset, and a user that has access rights to that dataset.

$dataset = $freenas->createDataset(
    'store01',    // pool
    'my-dataset', // dataset
    20 * 1024**3  // size in bytes
);

$user = $freenas->createUser(
    1001,                        // uid
    'my-user',                   // username
    $dataset->getMountPoint(),   // homedir
    'SuperSecretUserPassword123' // password
);
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
