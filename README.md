# FreeNAS PHP SDK

This package is built upon the v2.0 of the FreeNAS API.
* [FreeNAS API v2.0 REST documentation](https://api.ixsystems.com/freenas/)

This package is closed source.

## How to develop

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix). 
* Also make sure PHPStan does not find any bugs.

```bash

vendor/bin/php-cs-fixer fix

vendor/bin/phpstan analyze

vendor/bin/phpunit --coverage-text

```

These tools will also run in GitLab CI on MR's and pushes on master.
