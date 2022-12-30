<?php
/**
 * This sample follows the example code from the Wikipedia article.
 * @link https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm
 */
use g105b\Graph\Connection;
use g105b\Graph\Graph;
use g105b\Graph\Node;

require __DIR__ . "/../vendor/autoload.php";

$nodeArray = [
	1 => new Node("Node1"),
	2 => new Node("Node2"),
	3 => new Node("Node3"),
	4 => new Node("Node4"),
	5 => new Node("Node5"),
	6 => new Node("Node6"),
];

$graph = new Graph(...$nodeArray);
$graph->connectBothWays($nodeArray[1], $nodeArray[2], 7, 7);
$graph->connectBothWays($nodeArray[1], $nodeArray[3], 9, 9);
$graph->connectBothWays($nodeArray[1], $nodeArray[6], 14, 14);
$graph->connectBothWays($nodeArray[2], $nodeArray[3], 10, 10);
$graph->connectBothWays($nodeArray[2], $nodeArray[4], 15, 15);
$graph->connectBothWays($nodeArray[3], $nodeArray[6], 2, 2);
$graph->connectBothWays($nodeArray[3], $nodeArray[4], 11, 11);
$graph->connectBothWays($nodeArray[4], $nodeArray[5], 6, 6);
$graph->connectBothWays($nodeArray[5], $nodeArray[6], 9, 9);

$callback = function(Node $node, Connection $connection):void {
	echo "Visited $node, connected to $connection->to, weight $connection->weight", PHP_EOL;
};

$startTime = microtime(true);
$path = $graph->findShortestPath($nodeArray[1], $nodeArray[5], $callback);

echo "Shortest path: ", implode("->", $path), PHP_EOL;
echo "Total time: ", number_format(microtime(true) - $startTime, 6), " seconds", PHP_EOL;
