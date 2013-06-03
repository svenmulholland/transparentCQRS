<?php
namespace TransparentCQRS\Command\Bus;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Factories\CommandHandlerFactory;
use Zend\Di\Di;

/**
 * Class AbstractCommandBus
 * @package TransparentCQRS\Command\Bus
 */
abstract class AbstractCommandBus {

	/**
	 * @var CommandHandlerFactory
	 */
	protected $commandHandlerFactory;

	/**
	 * @param CommandHandlerFactory $commandHandlerFactory
	 */
	public function setCommandHandlerFactory(CommandHandlerFactory $commandHandlerFactory) {
		$this->commandHandlerFactory = $commandHandlerFactory;
	}

	/**
	 * @param Command $command
	 * @return mixed
	 */
	protected function getHandler(Command $command) {
		$commandHandler = $this->commandHandlerFactory->create($command);

		return $commandHandler;
	}

	/**
	 * @param Command $command
	 */
	public abstract function send(Command $command);
}