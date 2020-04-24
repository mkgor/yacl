![](https://img.shields.io/badge/coverage-94%25-green)
![GitHub](https://img.shields.io/github/license/mkgor/yacl)
![GitHub All Releases](https://img.shields.io/github/downloads/mkgor/yacl/total)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/mkgor/yacl)

# YACL
##### Yet Another Configuration Language

![Logo](https://i.imgur.com/BP0GNRS.jpg)

YACL is lightweight and easy to use library, which allows you to make your configuration files easy to read and edit. 

## Requirements

- Composer (for installation)
- PHP 7.1 or higher

## Installation

Use composer to install it

````bash
composer require mkgor/yacl
````

## Usage

After installation, you are ready to use it

````php
<?php

$result = $this->manager->parseYcl('path-to-your-file.ycl');

//Getting data as array
$configurationArray = $result->asArray();

//Getting data as PHP object
$configurationObject = $result->asObject();
````

## Syntax

Creating simple key => value item

````
key is "value"
````

Creating array / multidimensional array

````
arrayName are
  key is "value"
  
  secondArray are
    secondKey is "secondValue"
  end
end
````

You also can create inline array and specify its items in one line

````
inlineArray are "firstValue","secondValue","thirdValue" end
````

#### Syntax highlighting

In near future i will create .ycl syntax highlighting plugin for Sublime Text
