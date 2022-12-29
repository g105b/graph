<?php
namespace g105b\Graph;

class Graph {
	/** @var array<Node> */
	private array $nodeArray;
	/** @var array<Connection> */
	private array $connectionArray;

	public function __construct(Node...$nodeArray) {
		$this->nodeArray = $nodeArray;
		$this->connectionArray = [];
	}

	public function addNode(Node $node):void {
		array_push($this->nodeArray, $node);
	}

	public function hasNode(Node $node):bool {
		return in_array($node, $this->nodeArray, true);
	}

	public function addConnection(Connection $connection):void {
		array_push($this->connectionArray, $connection);
	}

	public function isConnected(Node $nodeFrom, Node $nodeTo):bool {
		$allConnections = $this->getConnectionsFrom($nodeFrom);
		foreach($allConnections as $connection) {
			if($connection->isFrom($nodeFrom) && $connection->isTo($nodeTo)) {
				return true;
			}
		}

		return false;
	}

	/** @return array<Connection> */
	public function getConnectionsFrom(Node $node):array {
		return array_filter(
			$this->connectionArray,
			fn(Connection $connection) => $connection->isFrom($node)
		);
	}

	/** @return array<Connection> */
	public function getConnectionsTo(Node $node):array {
		return array_filter(
			$this->connectionArray,
			fn(Connection $connection) => $connection->isTo($node)
		);
	}
}
