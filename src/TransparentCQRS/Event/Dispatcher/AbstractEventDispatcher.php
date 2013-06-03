<?php

namespace TransparentCQRS\Event\Dispatcher;

use Icecave\Collections\Set;
use TransparentCQRS\Event\Handler\EventHandler;

abstract class AbstractEventDispatcher implements Dispatcher {
	/** @var  Set */
	protected $registeredEventHandlers;

	public function registerEventHandler(EventHandler $eventHandler) {
		$this->registeredEventHandlers->add($eventHandler);
	}
}