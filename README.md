Sally's Laboratory Information Management System
================================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/littlerobot/slims/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/littlerobot/slims/?branch=develop)

## Running application locally
## Quick start (Mac/Linux)
* Install [VirtualBox](https://www.virtualbox.org/) and [Vagrant](https://www.vagrantup.com/)
* Clone the `develop` branch to your local machine
* $ `vagrant up` - you will be prompted to enter your local account password to add an NFS share (this offers better performace but won't work for Windows users)
* `vagrant ssh`
* `cd /var/www`
* `composer install --prefer-dist`
* Enter the following values when prompted:
  * DB name: _slims_
  * DB user: _slims_
  * DB password: _slims_
  * All the other values can be left as defaults
* `php app/console doctrine:migrations:migrate`
* `php app/console doctrine:fixtures:load --purge-with-truncate`
* `bash install-extjs.sh`
* Add an `/etc/hosts` entry: `192.168.56.101 slims.dev`
* Visit http://slims.dev/app_dev.php
* Login with the user _test0001_ and the password _test_
 

## Quick start (Windows)
* Install PHP 5.5.x, MySQL 5.5.x and [Composer](https://getcomposer.org/)
* Clone the `develop` branch to your local machine.
* `cd path\to\slims\directory`
* `php path\to\composer install --prefer-dist`
* Enter the correct database credentials
* `php app\console doctrine:migrations:migrate`
* `php app\console doctrine:fixtures:load --purge-with-truncate`
* Download [ExtJS 4.2.1](http://cdn.sencha.com/ext/gpl/ext-4.2.1-gpl.zip) and extract to `web\ext-4.2`
* `php app\console server:run`
* Visit the URL displayed on the command line. **Be sure to add app_dev.php to the end.**
* Login with the user _test0001_ and the password _test_
