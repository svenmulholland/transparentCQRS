<?php

namespace TransparentCQRS\Command\Handler\Reflection;


use Doctrine\Common\Annotations\Reader;
use Icecave\Collections\Queue;
use Icecave\Collections\Set;

class CommandHandlerReflector {
	private $annotationReader;

	public function __construct(Reader $annotationReader) {
		$this->annotationReader = $annotationReader;
	}

	public function getTheHandledCommandOfTheCommandHandler($commandHandler) {
		$reflectionMethod = new \ReflectionMethod($commandHandler, "handle");
		$params = $reflectionMethod->getParameters();
		return $params[0]->getClass()->name;
	}

	public function getDecorationSequence($commandHandler, Set $knownCommandHandlers) {
		$decoratorSequence = new Queue();

		$annotationsToSatisfy = $this->annotationReader->getMethodAnnotations(
			new \ReflectionMethod($commandHandler, "handle")
		);

		foreach($annotationsToSatisfy as $nextAnnotationToSatisfy) {
			$commandHandlerIterator = $knownCommandHandlers->getIterator();
			$commandHandlerIterator->rewind();
			while ($commandHandlerIterator->valid()) {
				$actualCommandHandler = $commandHandlerIterator->current();

				$reflectedCommandHandler = new \ReflectionClass($actualCommandHandler);
				$commandHandlerClassAnnotations = $this->annotationReader->getClassAnnotations($reflectedCommandHandler);
				foreach ($commandHandlerClassAnnotations AS $annotation) {
					if ($annotation == $nextAnnotationToSatisfy) {
						$decoratorSequence->push($actualCommandHandler);
						$childDecoratorSequence = $this->getDecorationSequence($actualCommandHandler, $knownCommandHandlers);
						while (!$childDecoratorSequence->isEmpty()) {
							$childDecorator = $childDecoratorSequence->pop();
							$decoratorSequence->push($childDecorator);
						}
					}
				}

				$commandHandlerIterator->next();
			}
		}

		return $decoratorSequence;
	}
}