<?php
namespace g105b\Graph;

use Stringable;

class Node implements Stringable {
	public function __construct(public readonly string $name) {}

	public function __toString():string {
		return $this->name;
	}
}
