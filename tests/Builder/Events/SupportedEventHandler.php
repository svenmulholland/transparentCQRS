<?php

namespace Builder\Events;


use TransparentCQRS\Event\Event;
use TransparentCQRS\Event\Handler\EventHandler;

class SupportedEventHandler implements EventHandler {
	public function handle(Event $event) {
		echo "HANDLED SUPPORT EVENT";
	}
}