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
$graph->connectBidirectional($nodeArray[1], $nodeArray[2], 7, 7);
$graph->connectBidirectional($nodeArray[1], $nodeArray[3], 9, 9);
$graph->connectBidirectional($nodeArray[1], $nodeArray[6], 14, 14);
$graph->connectBidirectional($nodeArray[2], $nodeArray[3], 10, 10);
$graph->connectBidirectional($nodeArray[2], $nodeArray[4], 15, 15);
$graph->connectBidirectional($nodeArray[3], $nodeArray[6], 2, 2);
$graph->connectBidirectional($nodeArray[3], $nodeArray[4], 11, 11);
$graph->connectBidirectional($nodeArray[4], $nodeArray[5], 6, 6);
$graph->connectBidirectional($nodeArray[5], $nodeArray[6], 9, 9);

$callback = function(Node $node, Connection $connection, float $distance, int $index):void {
	echo "Visited $node, connected to $connection->to, weight $connection->weight, distance $distance (step $index)", PHP_EOL;
};

$startTime = microtime(true);
$path = $graph->findShortestPath($nodeArray[1], $nodeArray[5], $callback);

echo "Shortest path: ", implode("->", $path), PHP_EOL;
echo "Total time: ", number_format(microtime(true) - $startTime, 6), " seconds", PHP_EOL;

/*
Example output:
Visited Node1, connected to Node2, weight 7, distance 7 (step 0)
Visited Node1, connected to Node3, weight 9, distance 9 (step 0)
Visited Node1, connected to Node6, weight 14, distance 14 (step 0)
Visited Node2, connected to Node1, weight 7, distance 14 (step 1)
Visited Node2, connected to Node3, weight 10, distance 17 (step 1)
Visited Node2, connected to Node4, weight 15, distance 22 (step 1)
Visited Node3, connected to Node1, weight 9, distance 18 (step 2)
Visited Node3, connected to Node2, weight 10, distance 19 (step 2)
Visited Node3, connected to Node6, weight 2, distance 11 (step 2)
Visited Node3, connected to Node4, weight 11, distance 20 (step 2)
Visited Node6, connected to Node1, weight 14, distance 25 (step 3)
Visited Node6, connected to Node3, weight 2, distance 13 (step 3)
Visited Node6, connected to Node5, weight 9, distance 20 (step 3)
Visited Node6, connected to Node1, weight 14, distance 25 (step 4)
Visited Node6, connected to Node3, weight 2, distance 13 (step 4)
Visited Node6, connected to Node5, weight 9, distance 20 (step 4)
Visited Node4, connected to Node2, weight 15, distance 35 (step 5)
Visited Node4, connected to Node3, weight 11, distance 31 (step 5)
Visited Node4, connected to Node5, weight 6, distance 26 (step 5)
Shortest path: Node1->Node3->Node6->Node5
Total time: 0.000168 seconds
*/
