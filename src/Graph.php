<?php
namespace g105b\Graph;

use SplPriorityQueue;

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
		return array_filter(
			$this->connectionArray,
			fn(Connection $connection) => $connection->isTo($node)
		);
	}

	/** @return array<Connection> */
	public function getAllConnections(Node $node):array {
		return array_filter(
			$this->connectionArray,
			fn(Connection $connection) => $connection->isFrom($node) || $connection->isTo($node)
		);
	}

	/** @return array<Node> */
	public function findShortestPath(Node $from, Node $to):array {
		foreach([$from, $to] as $node) {
			if(!$this->hasNode($node)) {
				throw new NodeNotInGraphException($node);
			}
		}

		$queue = new SplPriorityQueue();
		$distance = [];
		$previous = [];

		// Reset all vertices to infinity distance:
		$distance[spl_object_id($from)] = 0;
		foreach($this->nodeArray as $node) {
			$id = spl_object_id($node);
			if($from !== $node) {
				$distance[$id] = INF;
			}
			$previous[$id] = null;
			$queue->insert($node, -1*$distance[$id]);
		}

		// Dijkstra's algorithm:
		for($i = 0, $len = count($this->nodeArray) * count($this->nodeArray); $i <= $len; $i++) {
			if($queue->count() === 0) {
				break;
			}
			$nextNode = $queue->extract();
			if($nextNode === $to) {
				break;
			}

			$id = spl_object_id($nextNode);
			foreach($this->getAllConnections($nextNode) as $connection) {
				$newNode = ($connection->to === $nextNode) ? $connection->from : $connection->to;
				$newId = spl_object_id($newNode);
				$newDistance = $connection->weight + $distance[$id];

				if($newDistance < $distance[$newId]) {
					$distance[$newId] = $newDistance;
					$previous[$newId] = $nextNode;
					$queue->insert($newNode, -1 * $distance[$newId]);
				}
			}
		}

		$result = [];
		$id = spl_object_id($to);

		if(!$previous[$id]) {
			throw new NodesNotConnectedException();
		}

		array_push($result, $to);
		for($i = 0, $len = count($this->nodeArray); $i <= $len; $i++) {
			if(!$previous[$id]) {
				break;
			}

			array_push($result, $previous[$id]);
			$id = spl_object_id($previous[$id]);
		}

		return array_reverse($result);
	}
}
