<?php

namespace Builder\Events;


use TransparentCQRS\Event\Event;

class EventBuilder {
	private $event;

	public static function anSupportedEvent() {
		$aggregateBuilder = new EventBuilder(
			new SupportedEvent()
		);
		return $aggregateBuilder;
	}

	public static function anUnsupportedEvent() {
		$aggregateBuilder = new EventBuilder(
			new UnsupportedEvent()
		);
		return $aggregateBuilder;
	}

	private function __construct(Event $event) {
		$this->event = $event;
	}

	public function build() {
		return $this->event;
	}
}