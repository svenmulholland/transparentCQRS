<?php

namespace TransparentCQRS\Command\Bus;

use TransparentCQRS\Command\Command;

class InMemoryCommandBus extends CommandBus {

	public function __construct() {
		$this->initHandlers();
	}

	public function send(Command $command) {
		if ($this->commandHandlers->offsetExists($this->commandName($command))) {
			$commandHandler = $this->commandHandlers->offsetGet($this->commandName($command));
			$commandHandler->handle($command);
		} else {
			throw new \DomainException("There's no CommandHandler for the " . get_class($command) . " type");
		}
	}

	private function commandName(Command $command) {
		return get_class($command);
	}
}

?>