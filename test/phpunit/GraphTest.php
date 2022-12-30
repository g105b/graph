<?php
namespace g105b\Graph\Test;

use g105b\Graph\Connection;
use g105b\Graph\Graph;
use g105b\Graph\GraphException;
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
		$sut->findShortestPath($n1, $n2);
	}

	public function testFindShortestPath_oneWay():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2);
		$sut->connect($n2, $n1);
		self::expectException(NodesNotConnectedException::class);
		$sut->findShortestPath($n1, $n2);
	}

	public function testFindShortestPath_onlyTwoNodes():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2);
		$sut->connect($n1, $n2);
		self::assertSame([$n1, $n2], $sut->findShortestPath($n1, $n2));
	}

	public function testFindShortestPath_threeNodes():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$n3 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2, $n3);
		$sut->connect($n1, $n2);
		$sut->connect($n2, $n3);
		self::assertSame([$n1, $n2, $n3], $sut->findShortestPath($n1, $n3));
	}

	public function testFindShortestPath_directPathLonger():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$n3 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2, $n3);
		$sut->connect($n1, $n3, 3);
		$sut->connect($n1, $n2, 1);
		$sut->connect($n2, $n3, 1);
		self::assertSame([$n1, $n2, $n3], $sut->findShortestPath($n1, $n3));
	}

	public function testFindShortestPath_skipMultipleHops():void {
		$n1 = self::createMock(Node::class);
		$n2 = self::createMock(Node::class);
		$n3 = self::createMock(Node::class);
		$n4 = self::createMock(Node::class);
		$n5 = self::createMock(Node::class);
		$n6 = self::createMock(Node::class);
		$n7 = self::createMock(Node::class);
		$n8 = self::createMock(Node::class);
		$sut = new Graph($n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8);
		$sut->connect($n1, $n2);
		$sut->connect($n2, $n3);
		$sut->connect($n3, $n4);
		$sut->connect($n4, $n5);
		$sut->connect($n5, $n6);
		$sut->connect($n6, $n7);
		$sut->connect($n7, $n8);

		$sut->connect($n3, $n6);
		$sut->connect($n6, $n8);

		self::assertSame([$n1, $n2, $n3, $n6, $n8], $sut->findShortestPath($n1, $n8));
	}

	public function testFindShortestPath_hundredsOfNodes():void {
		$nodeArray = [];

		for($i = 0; $i < 1_000; $i++) {
			$node = self::createMock(Node::class);
			array_push($nodeArray, $node);
		}
		$sut = new Graph(...$nodeArray);

		for($i = 0; $i < 100; $i++) {
			$fromIndex = array_rand($nodeArray);
			do {
				$toIndex = array_rand($nodeArray);
			}
			while($fromIndex === $toIndex);
			$sut->connect($nodeArray[$fromIndex], $nodeArray[$toIndex]);
		}

		// Add a definite connection between two nodes, with 10 steps.
		$stepIndexArray = [];
		for($i = 0; $i < 10; $i++) {
			do {
				$stepIndex = array_rand($nodeArray);
			}
			while(in_array($stepIndex, $stepIndexArray));
			array_push($stepIndexArray, $stepIndex);
		}
		$prevStepIndex = null;
		foreach($stepIndexArray as $stepIndex) {
			if($prevStepIndex) {
				$sut->connect($nodeArray[$prevStepIndex], $nodeArray[$stepIndex]);
			}

			$prevStepIndex = $stepIndex;
		}
		$fromNode = $nodeArray[$stepIndexArray[0]];
		$toNode = $nodeArray[$stepIndexArray[9]];

		$exception = null;
		try {
			$path = $sut->findShortestPath($fromNode, $toNode);
			self::assertLessThan(1000, count($path));
		}
		catch(GraphException $exception) {}
		self::assertNull($exception);
	}

	public function testFindShortestPath_callback():void {
		$node1 = self::createMock(Node::class);
		$node2 = self::createMock(Node::class);
		$node3 = self::createMock(Node::class);
		$node4 = self::createMock(Node::class);

		$sut = new Graph($node1, $node2, $node3, $node4);
		$sut->connect($node1, $node2);
		$sut->connect($node2, $node3);
		$sut->connect($node3, $node4);

		$callbackCount = 0;
		$callback = function(Node $node, Connection $connection)use(&$callbackCount) {
			$callbackCount++;
		};

		$sut->findShortestPath($node1, $node4, $callback);
		self::assertSame(3, $callbackCount);
	}
}
