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
		foreach($this->getConnectionsFrom($nodeFrom) as $fromConnection) {
			if($fromConnection->isTo($nodeTo)) {
				return true;
			}
		}

		return false;
	}

	public function connect(Node $n1, Node $n2, float $weight = 1.0):void {
		$connection = new Connection($n1, $n2, $weight);
		array_push($this->connectionArray, $connection);
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
		$filtered = array_filter(
			$this->connectionArray,
			fn(Connection $connection) => $connection->isTo($node)
		);
		return $filtered;
	}

	/** array<Node> */
	public function findShortestPath(Node $from, Node $to):array {
		foreach([$from, $to] as $node) {
			if(!$this->hasNode($node)) {
				throw new NodeNotInGraphException($node);
			}
		}

		$path = [$from, $to];
		return $path;
	}
}
