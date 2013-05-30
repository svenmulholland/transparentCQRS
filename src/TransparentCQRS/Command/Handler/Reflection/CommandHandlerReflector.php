<?php

namespace TransparentCQRS\Command\Handler\Reflection;


use Doctrine\Common\Annotations\Reader;
use Icecave\Collections\Set;
use Icecave\Collections\Stack;
use TransparentCQRS\Command\Handler\Annotations\DecoratedBy;

class CommandHandlerReflector {
	CONST IS_COMMAND_HANDLER_ANNOTATION = "TransparentCQRS\Command\Handler\Annotations\IsCommandHandler";
	CONST HANDLES_ANNOTATION = "TransparentCQRS\Command\Handler\Annotations\Handles";

	private $annotationReader;

	public function __construct(Reader $annotationReader) {
		$this->annotationReader = $annotationReader;
	}

	public function getTheHandledCommandOfTheCommandHandler($commandHandler) {
		$theSupportedCommand = $this->annotationReader->getMethodAnnotation(
			new \ReflectionMethod($commandHandler, "handle"),
			self::HANDLES_ANNOTATION
		);

		if ($theSupportedCommand != null) {
			return $theSupportedCommand->command;
		}
	}

	public function getDecorationSequence($commandHandler, Set $knownCommandHandlers) {
		$decoratorSequence = new Stack();

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
							$decoratorSequence->push($actualCommandHandler);
							$childDecoratorSequence = $this->getDecorationSequence($actualCommandHandler, $knownCommandHandlers);
							while (!$childDecoratorSequence->isEmpty()) {
								$childDecorator = $childDecoratorSequence->pop();
								$decoratorSequence->push($childDecorator);
							}
						}

						$commandHandlerIterator->next();
					}
				}
			}
		}

		return $decoratorSequence;
	}
}