<?php
namespace g105b\Graph\Test;

use g105b\Graph\Connection;
use g105b\Graph\Graph;
use g105b\Graph\Node;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase {
	public function testHasNode_no():void {
		$node = self::createMock(Node::class);
		$sut = new Graph();
		self::assertFalse($sut->hasNode($node));
	}

	public function testHasNode():void {
		$node = self::createMock(Node::class);
		$sut = new Graph($node);
		self::assertTrue($sut->hasNode($node));
	}

	public function testHasNode_many():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$n3 = self::createMock(Node::class);

		$sut = new Graph($n1, $n2);
		self::assertTrue($sut->hasNode($n1));
		self::assertTrue($sut->hasNode($n2));
		self::assertFalse($sut->hasNode($n3));
	}

	public function testAddNode():void {
		$sut = new Graph();
		$node = self::createMock(Node::class);
		self::assertFalse($sut->hasNode($node));
		$sut->addNode($node);
		self::assertTrue($sut->hasNode($node));
	}

	public function testAddConnection():void {
		$sut = new Graph();
		$node1 = self::createMock(Node::class);
		$node2 = self::createMock(Node::class);
		$connection = self::createMock(Connection::class);
		$connection->method("isFrom")
			->with($node1)
			->willReturn(true);
		$connection->method("isTo")
			->with($node2)
			->willReturn(true);
		self::assertFalse($sut->isConnected($node1, $node2));
		$sut->addConnection($connection);
		self::assertTrue($sut->isConnected($node1, $node2));
	}

	public function testAddConnection_notConnected():void {
		$sut = new Graph();
		$node1 = self::createMock(Node::class);
		$node2 = self::createMock(Node::class);
		$node3 = self::createMock(Node::class);
		$connection = self::createMock(Connection::class);

		$connection->method("isFrom")
			->with($node1)
			->willReturn(true);
		$connection->method("isTo")
			->with($node2)
			->willReturn(false);
		$connection->method("isTo")
			->with($node3)
			->willReturn(true);

		$connection = new Connection($node1, $node3);

		$sut->addConnection($connection);
		self::assertFalse($sut->isConnected($node1, $node2));
		self::assertTrue($sut->isConnected($node1, $node3));
	}
}
