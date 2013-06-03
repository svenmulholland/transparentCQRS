<?php

namespace TransparentCQRS\Event\Dispatcher\Threaded;


use Aza\Components\Thread\ThreadPool;
use Icecave\Collections\Set;
use TransparentCQRS\Event\Dispatcher\AbstractEventDispatcher;
use TransparentCQRS\Event\Event;

/**
 * Class ThreadedEventDispatcher
 * @package TransparentCQRS\Event\Dispatcher
 */
class ThreadedEventDispatcher extends AbstractEventDispatcher {
	/**
	 * @var \Aza\Components\Thread\ThreadPool
	 */
	private $threadPool;

	/**
	 * @param ThreadPool $threadPool
	 */
	public function __construct(ThreadPool $threadPool) {
		$this->registeredEventHandlers = new Set();
		$this->threadPool = $threadPool;
	}

	/**
	 * @param Event $event
	 * @throws \RuntimeException
	 */
	public function dispatch(Event $event) {
		$it = $this->registeredEventHandlers->getIterator();
		$it->rewind();
		while ($it->valid() && $this->threadPool->hasWaiting()) {
			$this->threadPool->run($it->current(), $event);
			$it->next();
		}

		$this->threadPool->wait($failed);

		if ($failed) {
			foreach ($failed as $threadId => $err) {
				list($errorCode, $errorMessage) = $err;
				throw new \RuntimeException("Error (thread $threadId): #$errorCode - $errorMessage");
			}
		}
	}

	/**
	 *
	 */
	public function __destruct() {
		$this->threadPool->cleanup();
	}
}