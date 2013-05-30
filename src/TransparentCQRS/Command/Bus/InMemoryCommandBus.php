<?php

namespace TransparentCQRS\Command\Bus;

use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Factory\CommandHandlerFactory;

class InMemoryCommandBus extends BaseCommandBus {

	public function __construct(CommandHandlerFactory $commandHandlerFactory) {
		$this->commandHandlerFactory = $commandHandlerFactory;
	}

	public function send(Command $command) {
		$commandHandler = $this->getHandler($command);
		$commandHandler->handle($command);
	}
}