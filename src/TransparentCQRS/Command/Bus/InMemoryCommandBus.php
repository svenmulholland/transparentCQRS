<?php

namespace TransparentCQRS\Command\Bus;

use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Factories\CommandHandlerFactory;

/**
 * Class InMemoryCommandBus
 * @package TransparentCQRS\Command\Bus
 */
class InMemoryCommandBus extends AbstractCommandBus {

	/**
	 * @param CommandHandlerFactory $commandHandlerFactory
	 */
	public function __construct(CommandHandlerFactory $commandHandlerFactory) {
		$this->commandHandlerFactory = $commandHandlerFactory;
	}

	/**
	 * @param Command $command
	 */
	public function send(Command $command) {
		$commandHandler = $this->getHandler($command);
		$commandHandler->handle($command);
	}
}