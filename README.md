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

Efficiency
----------

In this implementation, all nodes of the graph are imported into the Priority Queue. This is not required for the shortest path to be calculated, but is done this way for completeness and code readability. From testing, the underlying optimisations of the SplPriorityQueue provide their own optimisations, and no noticeable efficiency difference can be perceived.

[wiki-dijkstra]: https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm
[php-priority-queue]: https://www.php.net/manual/en/class.splpriorityqueue.php
[wiki-travelling-salesperson]: https://en.wikipedia.org/wiki/Travelling_salesperson_problem
