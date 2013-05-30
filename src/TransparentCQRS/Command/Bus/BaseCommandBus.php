<?php
namespace TransparentCQRS\Command\Bus;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Factory\CommandHandlerFactory;
use Zend\Di\Di;

abstract class BaseCommandBus {

	protected $commandHandlerFactory;

	public function setCommandHandlerFactory(CommandHandlerFactory $commandHandlerFactory) {
		$this->commandHandlerFactory = $commandHandlerFactory;
	}

	protected function getHandler(Command $command) {
		$commandHandler = $this->commandHandlerFactory->create($command);

		return $commandHandler;
	}

	public abstract function send(Command $command);
}