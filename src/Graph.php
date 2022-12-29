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
}
