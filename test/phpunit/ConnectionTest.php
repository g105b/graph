<?php
namespace g105b\Graph\Test;

use g105b\Graph\Connection;
use g105b\Graph\Node;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase {
	public function testIsConnected():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$sut = new Connection($n1, $n2);
		self::assertTrue($sut->isToOrFrom($n2));
	}

	public function testIsConnected_not():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$n3 = self::createMock(Node::class);
		$sut = new Connection($n1, $n3);
		self::assertFalse($sut->isToOrFrom($n2));
	}
}
