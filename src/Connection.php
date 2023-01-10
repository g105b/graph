<?php
namespace g105b\Graph;

class Connection {
	public function __construct(
		public readonly Node $from,
		public readonly Node $to,
		public readonly float $weight = 1,
	) {
		if($weight < 0) {
			throw new NegativeWeightException("A Connection cannot have a negative weight");
		}
	}

	public function isFrom(Node $node):bool {
		return $node === $this->from;
	}

	public function isTo(Node $node):bool {
		return $node === $this->to;
	}

	public function isToOrFrom(Node $node):bool {
		return $this->isFrom($node) || $this->isTo($node);
	}
}
