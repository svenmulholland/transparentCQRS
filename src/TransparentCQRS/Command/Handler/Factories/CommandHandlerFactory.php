<?php
namespace TransparentCQRS\Command\Handler\Factories;

use TransparentCQRS\Command\Handler\CommandHandler;

/**
 * Class CommandHandlerFactory
 * @package TransparentCQRS\Command\Handler\Factories
 */
interface CommandHandlerFactory {

	/**
	 * @param $handlerClassName
	 * @return mixed
	 */
	public function registerCommandHandler($handlerClassName);

	/**
	 * @param array $handlerClassNames
	 */
	public function setCommandHandlers(array $handlerClassNames);

	/**
	 * @param $handlerClassName
	 * @return CommandHandler
	 */
	public function create($handlerClassName);
}