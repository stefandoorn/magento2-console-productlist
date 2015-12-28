# magento2-console-productlist

Easy console module to get product list

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

Commands available:

### products:types 
Shows product types available
 
### products:list 
Shows all products

* Include --active to only get active products
* Include <typeName> to get only products of certain type
* Include --count to only get a count, instead of a list
* Example: php bin/magento products:list simple --active --count