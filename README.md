Implementation of Dijkstra's algorithm
======================================

Finds the shortest path between two nodes in a graph, featuring asymmetric, weighted connections.

[See the Wikipedia article on Dijkstra's algorithm][wiki-dijkstra].

This repository represents a `Graph` object, which can contain multiple `Node` objects. Nodes can be given multiple `Connection` objects, which connect two Nodes -- in one direction -- with a weight. A bidirectional connection requires two separate connections, and a bidirectional connection may have different weights in either direction.

Given a starting `Node` and an ending `Node`, the algorithm provides an array of `Node` objects that represent the shortest path between the `Connection`s in the `Graph`.

The underlying data structure in this implementation is a [Priority Queue][php-priority-queue].

Example usages
--------------

* In a city, many roads are connected with junctions. Some roads are faster than others. With a list of all junctions as Nodes, and all roads as Connections, from point A to point B (any two junctions) in the city, what is the path that takes the least amount of travel time, given that longer roads may have faster speeds? This is sometimes known as "[The Travelling Salesperson problem][wiki-travelling-salesperson]".
* In computer networks, multiple devices are connected with physical wire or wireless radio links. Each link has its own bandwidth. When transferring data between device A and device B, calculations can be made to optimise speed of transfer versus cost of bandwidth. 
* In a national electrical grid simulation, there will be hundreds of parallel routes, all with different lengths and number of connected homes/businesses. The flow of electricity will always flow through the path of least resistance, and calculating this path for the simulation will allow for better efficiencies in the grid.
* A large social network of a billion users may need to provide context to connections outside your personal network - suggesting that you might know person A because you are connected to person B, who is connected to person C, who in turn, is connected to person A.

Complexity & efficiency
-----------------------

In this implementation, all nodes of the graph are imported into the Priority Queue. This is not required for the shortest path to be calculated, but is done this way for completeness and code readability. From testing, the underlying optimisations of the SplPriorityQueue provide their own optimisations, and no noticeable efficiency difference can be perceived when only adding the nodes as they are detected.

Below is the result of a stress test, available at `example/complexity.php`, sorted by seconds taken to execute. The test was performed on a 2017 model ThinkPad X1 Carbon laptop, generation 6 (Intel Core vPro i7-8650U). Each row of the table represents a separate Graph, showing the total nodes in the first column and number of connections in the second column. Complexity is calculated by multiplying Nodes by Connections.

The `Graph::findShortestPath()` function takes an optional callback function. If provided, it is called for each connection of each discovered node. This is counted and labelled "Iterations". Seconds is calculated for the `findShortestPath` call, not including any setup time.

The Complexity:Seconds relationship is exponential, and begins rising past 1 second after 10,000 Nodes with 5,000 connections. 

|   Nodes | Connections |     Complexity | Iterations |  Seconds |
|--------:|------------:|---------------:|-----------:|---------:|
|      10 |           0 |              0 |          0 |    0.000 |
|      10 |           1 |             10 |          1 |    0.000 |
|      10 |           5 |             50 |          2 |    0.000 |
|      10 |          10 |            100 |          6 |    0.000 |
|     100 |           1 |            100 |          0 |    0.000 |
|     100 |          10 |          1,000 |          3 |    0.000 |
|     100 |          50 |          5,000 |         32 |    0.000 |
|     100 |         100 |         10,000 |         28 |    0.000 |
|   1,000 |          10 |         10,000 |          6 |    0.000 |
|   1,000 |         100 |        100,000 |         95 |    0.008 |
|  10,000 |         100 |      1,000,000 |         20 |    0.018 |
|   1,000 |         500 |        500,000 |        438 |    0.035 |
|   1,000 |       1,000 |      1,000,000 |        906 |    0.062 |
|  10,000 |      10,000 |    100,000,000 |        928 |    0.641 |
|  10,000 |       1,000 |     10,000,000 |        996 |    0.700 |
|  10,000 |       5,000 |     50,000,000 |      4,910 |    3.373 |
| 100,000 |       1,000 |    100,000,000 |        830 |    5.630 |
| 100,000 |      10,000 |  1,000,000,000 |      6,388 |   45.002 |
| 100,000 |      50,000 |  5,000,000,000 |     25,515 |  228.543 |
| 100,000 |     100,000 | 10,000,000,000 |     70,283 |  992.409 |

Future optimisations
--------------------

The repository is currently written as a pure implementation of the algorithm, but many future optimisations are possible. TThis can be achieved by extending the base classes provided in this repository.

Optimisations are often incredibly specific to particular use cases. A typical optimisation with this algorithm is to introduce caching, and an amount of estimation (a close-enough shortest path). This can be achieved by choosing how many steps to cache for each node (X), then storing a list of all connections within X steps. This information can be stored within the Graph, greatly reducing the time complexity required to calculate lengthy paths in enormous Graphs, with the trade-off of being an estimation, rather than a perfect calculation.

Another optimisation is to split the loop into multiple index-based chunks, so each chunk can be run in parallel on separate threads, or even separate computers.

[wiki-dijkstra]: https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm
[php-priority-queue]: https://www.php.net/manual/en/class.splpriorityqueue.php
[wiki-travelling-salesperson]: https://en.wikipedia.org/wiki/Travelling_salesperson_problem
