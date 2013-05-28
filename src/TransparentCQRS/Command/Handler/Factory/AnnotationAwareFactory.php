<?php

namespace TransparentCQRS\Command\Handler\Factory;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Icecave\Collections\Map;
use Icecave\Collections\Set;
use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Reflection\CommandHandlerReflector;
use Zend\Di\Di;

class AnnotationAwareFactory implements CommandHandlerFactory {

	private $dependencyInjectionContainer;
	private $commandHandlerReflector;
	private $alreadyConstructedCommandHandlers;

	public function __construct(Di $dependencyInjectionContainer,
								CommandHandlerReflector $commandHandlerReflector) {

		$this->commandHandlerReflector = $commandHandlerReflector;
		$this->dependencyInjectionContainer = $dependencyInjectionContainer;
		$this->alreadyConstructedCommandHandlers = new Map();
		$this->registeredCommandHandlerHandlers = new Set();
		AnnotationRegistry::registerAutoloadNamespace("TransparentCQRS\Command\Handler\Annotations", "/Users/sven/PhpstormProjects/transparentCQRS/src");
	}

	public function registerCommandHandler($handlerClassName) {
		$this->registeredCommandHandlerHandlers->add($handlerClassName);
	}

	public function create($command) {
		if ($this->alreadyConstructedCommandHandlers->hasKey($command) == false) {
			$handlerClassName = $this->getTheCommandHandlerThatCanHandleTheCommand($command);
			$commandHandler = $this->dependencyInjectionContainer->get($handlerClassName);
			$commandHandler = $this->decorateHandler($commandHandler);

			$this->alreadyConstructedCommandHandlers->add($command, $commandHandler);
		}

		return $this->alreadyConstructedCommandHandlers->get($command);
	}

	private function getTheCommandHandlerThatCanHandleTheCommand(Command $command) {
		$commandHandlerIterator = $this->registeredCommandHandlerHandlers->getIterator();
		$commandHandlerIterator->rewind();
		while($commandHandlerIterator->valid()) {
			$commandHandler = $commandHandlerIterator->current();
			if(get_class($command) == $this->commandHandlerReflector->getTheHandledCommandOfTheCommandHandler($commandHandler))
				return $commandHandler;
			$commandHandlerIterator->next();
		}
	}

	protected function decorateHandler(CommandHandler $commandHandler) {
		$decorationSequence = $this->commandHandlerReflector->getDecorationSequence(
			$commandHandler,
			$this->registeredCommandHandlerHandlers
		);


		while (!$decorationSequence->isEmpty()) {
			$decorator = $decorationSequence->pop();
			$commandHandler = new $decorator($commandHandler);
		}

		return $commandHandler;
	}
}