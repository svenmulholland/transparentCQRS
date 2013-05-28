<?php

namespace Builder\Aggregate;

use Builder\Events\SupportedEvent;
use TransparentCQRS\Aggregate\AggregateRoot;
use TransparentCQRS\Event\Event;

class TestAggregate extends AggregateRoot {

	public function doSomething() {
		$this->applyEvent(
			new SupportedEvent()
		);
	}

	protected function applySupportedEvent(Event $event) {
	}
}