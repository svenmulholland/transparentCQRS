<?php

namespace TransparentCQRS\Utilities\Annotation\CommandHandler;


use Doctrine\Common\Annotations\Reader;
use Icecave\Collections\Set;
use Icecave\Collections\Stack;
use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\Annotations\DecoratedBy;
use TransparentCQRS\Command\Handler\CommandHandler;

/**
 * Class Parser
 * @package TransparentCQRS\Utilities\Annotation\CommandHandler
 */
class Parser {
	/**
	 *
	 */
	CONST IS_COMMAND_HANDLER_ANNOTATION = "TransparentCQRS\Command\Handler\Annotations\IsCommandHandler";
	/**
	 *
	 */
	CONST HANDLES_ANNOTATION = "TransparentCQRS\Command\Handler\Annotations\Handles";

	/**
	 * @var \Doctrine\Common\Annotations\Reader
	 */
	private $annotationReader;
	/**
	 * @var \Icecave\Collections\Set
	 */
	private $registeredCommandHandlerHandlers;

	/**
	 * @param Reader $annotationReader
	 * @param Set $registeredCommandHandlerHandlers
	 */
	public function __construct(Reader $annotationReader, Set $registeredCommandHandlerHandlers) {
		$this->annotationReader = $annotationReader;
		$this->registeredCommandHandlerHandlers = $registeredCommandHandlerHandlers;
	}

	/**
	 * @param \Icecave\Collections\Set $registeredCommandHandlerHandlers
	 */
	public function setRegisteredCommandHandlerHandlers($registeredCommandHandlerHandlers) {
		$this->registeredCommandHandlerHandlers = $registeredCommandHandlerHandlers;
	}

	/**
	 * @param Command $command
	 * @return CommandHandler
	 */
	public function findTheHandlerClassNameForTheCommand(Command $command) {
		$commandHandlerIterator = $this->registeredCommandHandlerHandlers->getIterator();
		$commandHandlerIterator->rewind();
		while ($commandHandlerIterator->valid()) {
			$commandHandler = $commandHandlerIterator->current();
			if (get_class($command) == $this->getTheHandledCommandOfTheCommandHandler($commandHandler)) {
				return $commandHandler;
			}
			$commandHandlerIterator->next();
		}
	}

	/**
	 * @param $commandHandler
	 * @return string
	 */
	private function getTheHandledCommandOfTheCommandHandler($commandHandler) {
		$theSupportedCommand = $this->annotationReader->getMethodAnnotation(
			new \ReflectionMethod($commandHandler, "handle"),
			self::HANDLES_ANNOTATION
		);

		if ($theSupportedCommand != null) {
			return $theSupportedCommand->command;
		}
	}

	/**
	 * @param $commandHandler
	 * @param Set $knownCommandHandlers
	 * @return Stack
	 */
	public function commandHandlerPipeline($commandHandler, Set $knownCommandHandlers) {
		$commandHandlerPipeline = new Stack();

		$annotationsToSatisfy = $this->annotationReader->getMethodAnnotations(
			new \ReflectionMethod($commandHandler, "handle")
		);

		foreach ($annotationsToSatisfy as $nextAnnotationToSatisfy) {
			if ($nextAnnotationToSatisfy instanceof DecoratedBy) {
				foreach ($nextAnnotationToSatisfy->decorations as $decoration) {
					$commandHandlerIterator = $knownCommandHandlers->getIterator();
					$commandHandlerIterator->rewind();
					while ($commandHandlerIterator->valid()) {
						$actualCommandHandler = $commandHandlerIterator->current();

						$classAnnotation = $this->annotationReader->getClassAnnotation(
							new \ReflectionClass($actualCommandHandler),
							self::IS_COMMAND_HANDLER_ANNOTATION
						);

						if ($classAnnotation->name == $decoration) {
							$commandHandlerPipeline->push($actualCommandHandler);
							$childDecoratorSequence = $this->commandHandlerPipeline($actualCommandHandler, $knownCommandHandlers);
							while (!$childDecoratorSequence->isEmpty()) {
								$childDecorator = $childDecoratorSequence->pop();
								$commandHandlerPipeline->push($childDecorator);
							}
						}

						$commandHandlerIterator->next();
					}
				}
			}
		}

		return $commandHandlerPipeline;
	}
}