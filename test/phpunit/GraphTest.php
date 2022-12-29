<?php
namespace g105b\Graph\Test;

use g105b\Graph\Connection;
use g105b\Graph\Graph;
use g105b\Graph\Node;
use g105b\Graph\NodeNotInGraphException;
use g105b\Graph\NodesNotConnectedException;
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

	public function testGetConnectionsFrom():void {
		$sut = new Graph();
		$node1 = self::createMock(Node::class);
		$node2 = self::createMock(Node::class);
		$node3 = self::createMock(Node::class);
		$connection1to3 = self::createMock(Connection::class);
		$connection2to1 = self::createMock(Connection::class);

		$connection1to3->method("isFrom")
			->with($node1)
			->willReturn(true);
		$connection1to3->method("isFrom")
			->with($node2)
			->willReturn(false);
		$connection1to3->method("isFrom")
			->with($node3)
			->willReturn(false);

		$connection2to1->method("isFrom")
			->with($node1)
			->willReturn(false);
		$connection2to1->method("isFrom")
			->with($node2)
			->willReturn(true);
		$connection2to1->method("isFrom")
			->with($node3)
			->willReturn(false);

		$sut->addConnection($connection2to1);
		$sut->addConnection($connection1to3);
		self::assertContains($connection1to3, $sut->getConnectionsFrom($node1));
	}

	public function testGetConnectionsTo():void {
		$sut = new Graph();
		$connection1 = self::createMock(Connection::class);
		$connection2 = self::createMock(Connection::class);
		$node = self::createMock(Node::class);

		$connection1->expects(self::exactly(1))
			->method("isTo")
			->with($node);

		$sut->addConnection($connection1);
		$sut->addConnection($connection2);
		$sut->getConnectionsTo($node);
	}

	public function testConnect():void {
		$sut = new Graph();
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		self::assertFalse($sut->isConnected($n1, $n2));
		self::assertFalse($sut->isConnected($n2, $n1));
		$sut->connect($n1, $n2);
		self::assertTrue($sut->isConnected($n1, $n2));
		self::assertFalse($sut->isConnected($n2, $n1));
	}

	public function testFindShortestPath_nodeNotInGraph():void {
		$sut = new Graph();
		self::expectException(NodeNotInGraphException::class);
		$sut->findShortestPath(
			self::createMock(Node::class),
			self::createMock(Node::class),
		);
	}

	public function testFindShortestPath_notConnected():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2);
		self::expectException(NodesNotConnectedException::class);
		self::assertSame([$n1, $n2], $sut->findShortestPath($n1, $n2));
	}

	public function testFindShortestPath_onlyTwoNodes():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2);
		$sut->connect($n1, $n2);
		self::assertSame([$n1, $n2], $sut->findShortestPath($n1, $n2));
	}
}
