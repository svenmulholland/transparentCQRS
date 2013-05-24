<?php
namespace TransparentCQRS\Command\Bus;

use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;

abstract class CommandBus {
	protected $commandHandlers;

	protected function initHandlers() {
		$this->commandHandlers = new \ArrayObject();
	}

	public function register($commandClassName, CommandHandler $handler) {
		$this->commandHandlers->offsetSet($commandClassName, $handler);
	}

	public abstract function send(Command $command);
}

?>