<?php

/**
 * Test: Tracy\Dumper::toText() specials
 */

declare(strict_types=1);

use Tester\Assert;
use Tracy\Dumper;


require __DIR__ . '/../bootstrap.php';


// resource
Assert::match("stream resource @%d%\n   %S%%A%", Dumper::toText(fopen(__FILE__, 'r')));


// closure
Assert::match('Closure #%d%
   file: "%a%" (%i%)
   line: %i%
   variables: array ()
   parameters: ""
', Dumper::toText(function () {}));


// new class
Assert::match('class@anonymous #%d%', Dumper::toText(new class {
}));


// SplFileInfo
Assert::match('SplFileInfo #%d%
   path: "%a%" (%i%)
', Dumper::toText(new SplFileInfo(__FILE__)));


// SplObjectStorage
$objStorage = new SplObjectStorage;
$objStorage->attach($o1 = new stdClass);
$objStorage[$o1] = 'o1';
$objStorage->attach($o2 = (object) ['foo' => 'bar']);
$objStorage[$o2] = 'o2';

$objStorage->next();
$key = $objStorage->key();

Assert::match('SplObjectStorage #%d%
   0: array (2)
   |  object => stdClass #%d%
   |  data => "o1" (2)
   1: array (2)
   |  object => stdClass #%d%
   |  |  foo: "bar" (3)
   |  data => "o2" (2)
', Dumper::toText($objStorage));

Assert::same($key, $objStorage->key());


// ArrayObject
$obj = new ArrayObject(['a' => 1, 'b' => 2]);
Assert::match('ArrayObject #%d%
   storage: array (2)
   |  a => 1
   |  b => 2
', Dumper::toText($obj));

class ArrayObjectChild extends ArrayObject
{
	public $prop = 123;
}

$obj = new ArrayObjectChild(['a' => 1, 'b' => 2]);
Assert::match('ArrayObjectChild #%d%
   prop: 123
   storage: array (2)
   |  a => 1
   |  b => 2
', Dumper::toText($obj));
