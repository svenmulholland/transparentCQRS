<?php
namespace TransparentCQRS\Command\Bus;


use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Factories\CommandHandlerFactory;

/**
 * Class CommandBus
 * @package TransparentCQRS\Command\Bus
 */
interface CommandBus {
	/**
	 * @param CommandHandlerFactory $commandHandlerFactory
	 * @return mixed
	 */
	public function setCommandHandlerFactory(CommandHandlerFactory $commandHandlerFactory);

	/**
	 * @param Command $command
	 * @return mixed
	 */
	public function send(Command $command);
}