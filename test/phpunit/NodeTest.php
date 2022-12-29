<?php
namespace g105b\Graph\Test;

use g105b\Graph\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase {
	public function testToString():void {
		$sut = new Node("Test");
		self::assertSame("Test", (string)$sut);
	}
}
