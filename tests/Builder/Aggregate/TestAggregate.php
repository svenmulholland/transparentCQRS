<?php

namespace Builder\Aggregate;

use TransparentCQRS\Aggregate\AggregateRoot;
use TransparentCQRS\Event\Event;

class TestAggregate extends AggregateRoot {
	protected function applySupportedEvent(Event $event) {
	}
}