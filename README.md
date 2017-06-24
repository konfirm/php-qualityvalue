# Quality Values
Quality Values library for PHP (7+), for the parsing, sorting and filtering of Quality Value strings, such as the HTTP Accept(-Charset,-Encoding and -Language) headers. 

## Example use

```php
<?php

use Konfirm\QualityValue\Collection;

$collection = Collection::fromString('foo,qux;q=0,baz;q=0.8,bar;q=1');

//  turn back into a string
$proper = (string) $collection;

//  "foo,bar,baz;q=.8"
//  - sorted on weight (original order if the weight is equal)
//  - 'bar' has its weight (q=) removed as it's 1, which is the default
//  - 'qux' is removed as the weight 0 indicates "not acceptable") 

foreach ($collection as $token) {
	print $token->getValue();  //  'foo' > 'bar' > 'baz'
}

//  obtain the first token 

$first = $collection->rewind();  //  Konfirm\QualityValue\Token
print $first->getValue();   // 'foo'
print $first->getWeight();  //  1


//  ...and the next
$next = $collection->next();  //  Konfirm\QualityValue\Token
print $first->getValue();   // 'bar'
print $first->getWeight();  //  1

//  etc
```

## Features

* PSR-4 autoloading compliant structure
* Full code coverage with PHPUnit
