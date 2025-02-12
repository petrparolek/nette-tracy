<?php

/**
 * Test: Tracy\Dumper::toHtml() collapse (with snapshot)
 */

declare(strict_types=1);

use Tester\Assert;
use Tracy\Dumper;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/fixtures/DumpClass.php';


Assert::match('<pre class="tracy-dump" data-tracy-snapshot=\'[]\'><span class="tracy-toggle"><span class="tracy-dump-object">Test</span> <span class="tracy-dump-hash">#%d%</span></span>
<div><span class="tracy-dump-indent">   </span><span class="tracy-dump-public">x</span>: <span class="tracy-toggle tracy-collapsed" data-tracy-dump=\'[[0,10],[1,null]]\'><span class="tracy-dump-array">array</span> (2)</span>
<span class="tracy-dump-indent">   </span><span class="tracy-dump-private">y</span>: <span class="tracy-dump-string">"hello"</span> (5)
<span class="tracy-dump-indent">   </span><span class="tracy-dump-protected">z</span>: <span class="tracy-dump-number">30.0</span>
</div></pre>', Dumper::toHtml(new Test, [Dumper::COLLAPSE_COUNT => 1]));

Assert::match('<pre class="tracy-dump" data-tracy-snapshot=\'[]\'><span class="tracy-toggle"><span class="tracy-dump-object">Test</span> <span class="tracy-dump-hash">#%d%</span></span>
<div><span class="tracy-dump-indent">   </span><span class="tracy-dump-public">x</span>: <span class="tracy-toggle tracy-collapsed" data-tracy-dump=\'[[0,10],[1,null]]\'><span class="tracy-dump-array">array</span> (2)</span>
<span class="tracy-dump-indent">   </span><span class="tracy-dump-private">y</span>: <span class="tracy-dump-string">"hello"</span> (5)
<span class="tracy-dump-indent">   </span><span class="tracy-dump-protected">z</span>: <span class="tracy-dump-number">30.0</span>
</div></pre>', Dumper::toHtml(new Test, [Dumper::COLLAPSE_COUNT => 1, Dumper::COLLAPSE => false]));

Assert::match('<pre class="tracy-dump" data-tracy-snapshot=\'{"%d%":{"name":"Test","items":[["x",[[0,10],[1,null]],0],["y","hello",2],["z",{"number":"30.0"},1]]}}\'><span class="tracy-toggle tracy-collapsed" data-tracy-dump=\'{"object":%d%}\'><span class="tracy-dump-object">Test</span> <span class="tracy-dump-hash">#%d%</span></span>
</pre>', Dumper::toHtml(new Test, [Dumper::COLLAPSE => true]));

Assert::match('<pre class="tracy-dump" data-tracy-snapshot=\'{"%d%":{"name":"Test","items":[["x",[[0,10],[1,null]],0],["y","hello",2],["z",{"number":"30.0"},1]]}}\'><span class="tracy-toggle tracy-collapsed" data-tracy-dump=\'{"object":%d%}\'><span class="tracy-dump-object">Test</span> <span class="tracy-dump-hash">#%d%</span></span>
</pre>', Dumper::toHtml(new Test, [Dumper::COLLAPSE => 3]));
