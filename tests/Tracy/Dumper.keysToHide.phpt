<?php

declare(strict_types=1);

use Tester\Assert;
use Tracy\Dumper;


require __DIR__ . '/../bootstrap.php';


$obj = (object) [
	'a' => 456,
	'password' => 'secret1',
	'PASSWORD' => 'secret2',
	'Pin' => 'secret3',
	'inner' => [
		'a' => 123,
		'password' => 'secret4',
		'PASSWORD' => 'secret5',
		'Pin' => 'secret6',
	],
];


Assert::match('stdClass #%d%
   a: 456
   password: ***** (string)
   PASSWORD: ***** (string)
   Pin: ***** (string)
   inner: array (4)
   |  a => 123
   |  password => ***** (string)
   |  PASSWORD => ***** (string)
   |  Pin => ***** (string)
', Dumper::toText($obj, [Dumper::KEYS_TO_HIDE => ['password', 'PIN']]));


$snapshot = [];
Assert::match(
	'<pre class="tracy-dump" data-tracy-dump=\'{"object":%d%}\'></pre>',
	Dumper::toHtml($obj, [Dumper::KEYS_TO_HIDE => ['password', 'pin'], Dumper::SNAPSHOT => &$snapshot])
);

Assert::equal([
	[
		'name' => 'stdClass',
		'items' => [
			['a', 456, 3],
			['password', ['key' => '***** (string)'], 3],
			['PASSWORD', ['key' => '***** (string)'], 3],
			['Pin', ['key' => '***** (string)'], 3],
			[
				'inner',
				[
					['a', 123],
					['password', ['key' => '***** (string)']],
					['PASSWORD', ['key' => '***** (string)']],
					['Pin', ['key' => '***** (string)']],
				],
				3,
			],
		],
	],
], array_values(json_decode(explode("'", Dumper::formatSnapshotAttribute($snapshot))[1], true)));
