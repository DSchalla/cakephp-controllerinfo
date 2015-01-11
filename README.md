# ControllerInfo plugin for CakePHP

## Features

Provides information for every Controller like:

* Class (Includes full namespace)
* Methods
* Properties

## Usage

Install the Plugin as Regular and then call:

bin/cake ControllerInfo.Cache

This will gather information about all Controller in your src/ and plugin directories.

After that you can access the table ControllerInfo.Data to receive Controller Information. Methods and
Properties have to be unserialized before using the data.

## Installation

### Composer [NOT WORKING]
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require schalla/cakephp-controllerinfo
```

### GitHub

Download the ZIP, extract it in <PLUGINS>/ControllerInfo and update your Autoloader.

## Features & Bugs

Please report Bugs and Feature requests using GitHub Issues or drop me a message in the CakePHP IRC on Freenode.