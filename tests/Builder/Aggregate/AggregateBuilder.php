<?php

namespace Builder\Aggregate;

class AggregateBuilder {

	public static function anAggregate() {
		$aggregateBuilder = new AggregateBuilder();
		return $aggregateBuilder;
	}

	private function __construct() {
		$this->supportedEvents = new \ArrayObject();
	}

	public function build() {
		$aggregate = new TestAggregate();
		return $aggregate;
	}
}