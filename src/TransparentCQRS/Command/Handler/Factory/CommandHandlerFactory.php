<?php
namespace TransparentCQRS\Command\Handler\Factory;

interface CommandHandlerFactory {

	public function registerCommandHandler($handlerClassName);

	public function create($handlerClassName);
}