# magento2-console-productlist

Easy console module to show a product list on the console (with some filters available).

## Background

Module developed while researching capabilities of Magento 2, mainly as a test. Decided to push it to Github, can be used as an example module.

## Installation

Require through Composer:

````
composer require stefandoorn/magento2-console-productlist
````

Then enable the module:

````
php bin/magento module:enable --clear-static-content StefanDoorn_ConsoleProductList
````

And perform the install command:

````
php bin/magento setup:upgrade
````

## Commands

The commands below are available. Feel free to submit PRs to add additional features.

### products:types 
Shows product types available
 
### products:list <typeName> [--active] [--count]
Shows all products

* Include --active to only get active products
* Include <typeName> to get only products of certain type (optional)
* Include --count to only get a count, instead of a list
* Example: php bin/magento products:list simple --active --count