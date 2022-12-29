<?php
namespace g105b\Graph;

class Connection {
	public function __construct(
		public readonly Node $from,
		public readonly Node $to,
		public readonly float $weight = 1,
	) {}

	public function isConnected(Node $node):bool {
		return $node === $this->from || $node === $this->to;
	}
}
