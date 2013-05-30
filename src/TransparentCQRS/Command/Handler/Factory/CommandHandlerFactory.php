<?php
namespace TransparentCQRS\Command\Handler\Factory;

use TransparentCQRS\Command\Handler\CommandHandler;

interface CommandHandlerFactory {

	public function registerCommandHandler($handlerClassName);
	public function registerCommandHandlers(array $handlerClassNames);

	/** @var CommandHandler */
	public function create($handlerClassName);
}