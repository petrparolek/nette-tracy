<?php

/**
 * Test: Tracy\Dumper custom object exporters
 */

declare(strict_types=1);

use Tester\Assert;
use Tracy\Dumper;


require __DIR__ . '/../bootstrap.php';


$obj = new stdClass;
Assert::match('stdClass #%d%', Dumper::toText($obj));


$obj->a = 1;
Assert::match('stdClass #%d%
   a: 1
', Dumper::toText($obj));


$exporters = [
	'stdClass' => function ($var) {
		return ['x' => $var->a + 1];
	},
];
Assert::match('stdClass #%d%
   x: 2
', Dumper::toText($obj, [Dumper::OBJECT_EXPORTERS => $exporters])
);


$obj = unserialize('O:1:"Y":7:{s:1:"a";N;s:1:"b";i:2;s:4:"' . "\0" . '*' . "\0" . 'c";N;s:4:"' . "\0" . '*' . "\0" . 'd";s:1:"d";s:4:"' . "\0" . 'Y' . "\0" . 'e";N;s:4:"' . "\0" . 'Y' . "\0" . 'i";s:3:"bar";s:4:"' . "\0" . 'X' . "\0" . 'i";s:3:"foo";}');

Assert::match('__PHP_Incomplete_Class #%d%
   className: "Y"
   private: array (3)
   |  Y::$e => null
   |  Y::$i => "bar" (3)
   |  X::$i => "foo" (3)
   protected: array (2)
   |  c => null
   |  d => "d"
   public: array (2)
   |  a => null
   |  b => 2', Dumper::toText($obj));




Dumper::$objectExporters = [
	null => function ($var) { return ['type' => 'NULL']; },
	'Iterator' => function ($var) { return ['type' => 'Default Iterator']; },
];
$exporters = [
	'Iterator' => function ($var) { return ['type' => 'Iterator']; },
	'SplFileInfo' => function ($var) { return ['type' => 'SplFileInfo']; },
	'SplFileObject' => function ($var) { return ['type' => 'SplFileObject']; },
];
Assert::match('SplFileInfo #%d%
   type: "SplFileInfo" (11)
', Dumper::toText(new SplFileInfo(__FILE__), [Dumper::OBJECT_EXPORTERS => $exporters])
);
Assert::match('SplFileObject #%d%
   type: "SplFileObject" (13)
', Dumper::toText(new SplFileObject(__FILE__), [Dumper::OBJECT_EXPORTERS => $exporters])
);
Assert::match('ArrayIterator #%d%
   type: "Iterator" (8)
', Dumper::toText(new ArrayIterator([]), [Dumper::OBJECT_EXPORTERS => $exporters])
);
Assert::match('stdClass #%d%
   type: "NULL" (4)
', Dumper::toText(new stdClass, [Dumper::OBJECT_EXPORTERS => $exporters])
);
Assert::match('ArrayIterator #%d%
   type: "Default Iterator" (16)
', Dumper::toText(new ArrayIterator([]))
);
Assert::match('stdClass #%d%
   type: "NULL" (4)
', Dumper::toText(new stdClass)
);
