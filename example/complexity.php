<?php
use g105b\Graph\Connection;
use g105b\Graph\Graph;
use g105b\Graph\GraphException;
use g105b\Graph\Node;

require __DIR__ . "/../vendor/autoload.php";

// How many nodes in the graph for each test
$nodeCountTests = [10, 100, 1_000, 10_000, 100_000];
// How many connections for each test, relative to number of nodes
$connectionPercentageTests = [1, 10, 50, 100];

foreach($nodeCountTests as $nodeCount) {
	$nodeArray = [];
	for($i = 0; $i < $nodeCount; $i++) {
		$hex = dechex($i);
		$node = new Node("n$hex");
		array_push($nodeArray, $node);
	}

	foreach($connectionPercentageTests as $connectionPercentage) {
		$connectionCount = floor(($connectionPercentage / 100) * $nodeCount);
		echo "Testing ", number_format($nodeCount), " nodes ",
			"with ", number_format($connectionCount), " connections...",
			PHP_EOL;
		$graph = new Graph(...$nodeArray);
		$connectedIndices = [];
		for($i = 0; $i < $connectionCount; $i++) {
			do {
				$fromIndex = array_rand($nodeArray);
			}
			while(array_key_exists($fromIndex, $connectedIndices));
			if(!isset($connectedIndices[$fromIndex])) {
				$connectedIndices[$fromIndex] = [];
			}

			do {
				$toIndex = array_rand($nodeArray);
			}
			while($toIndex === $fromIndex || in_array($toIndex, $connectedIndices[$fromIndex]));
			array_push($connectedIndices[$fromIndex], $toIndex);
		}

		foreach($connectedIndices as $fromIndex => $toIndexArray) {
			$fromNode = $nodeArray[$fromIndex];
			foreach($toIndexArray as $toIndex) {
				$toNode = $nodeArray[$toIndex];
				$graph->connect($fromNode, $toNode);
			}
		}

		$fromIndex = array_rand($nodeArray);
		do {
			$toIndex = array_rand($nodeArray);
		}
		while($toIndex === $fromIndex);

		$fromNode = $nodeArray[$fromIndex];
		$toNode = $nodeArray[$toIndex];

		$callbackCount = 0;
		$callback = function(Node $node, Connection $connection)use(&$callbackCount):void {
			$callbackCount++;
		};

		$startTime = microtime(true);
		try {
			$graph->findShortestPath($fromNode, $toNode, $callback);
		}
		catch(GraphException) {}

		$seconds = microtime(true) - $startTime;

		echo "\tIterations = ", number_format($callbackCount), PHP_EOL;
		echo "\tSeconds = ", number_format($seconds, 4), PHP_EOL;
	}
}
