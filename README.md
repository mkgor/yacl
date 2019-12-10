![](https://img.shields.io/badge/coverage-94%25-green)
# YACL
##### Yet Another Configuration Language

YACL is lightweight and easy to use library, which allows you to make your configuration files easy to read and edit. 

## Installation

Use composer to install it

````bash
composer require pixaye/yacl
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

#### Important

You can't use integer as value, you should specify it as string
