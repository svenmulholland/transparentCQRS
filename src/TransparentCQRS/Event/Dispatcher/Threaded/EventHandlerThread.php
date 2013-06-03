<?php

namespace TransparentCQRS\Event\Dispatcher\Threaded;

use Aza\Components\Thread\Thread;
use TransparentCQRS\Event\Event;
use TransparentCQRS\Event\Handler\EventHandler;

/**
 * Class EventHandlerThread
 * @package TransparentCQRS\Event\Dispatcher
 */
class EventHandlerThread extends Thread {
	/**
	 * @var bool
	 */
	protected $argumentsMapping = true;

	/**
	 * @param EventHandler $eventHandler
	 * @param Event $event
	 */
	public function process(EventHandler $eventHandler, Event $event) {
		$eventHandler->handle($event);
	}
}