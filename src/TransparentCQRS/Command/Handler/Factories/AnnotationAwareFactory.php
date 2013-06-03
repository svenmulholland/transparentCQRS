<?php

namespace TransparentCQRS\Command\Handler\Factories;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Icecave\Collections\Map;
use Icecave\Collections\Set;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Utilities\Annotation\CommandHandler\Parser;
use Zend\Di\Di;

/**
 * Class AnnotationAwareFactory
 * @package TransparentCQRS\Command\Handler\Factories
 */
class AnnotationAwareFactory implements CommandHandlerFactory {

	/**
	 * @var \Zend\Di\Di
	 */
	private $dependencyInjectionContainer;
	/**
	 * @var \TransparentCQRS\Utilities\Annotation\CommandHandler\Parser
	 */
	private $commandHandlerParser;
	/**
	 * @var \Icecave\Collections\Map
	 */
	private $cachedCommandHandlerPipelines;

	/**
	 * @param Di $dependencyInjectionContainer
	 * @param \TransparentCQRS\Utilities\Annotation\CommandHandler\Parser $commandHandlerParser
	 */
	public function __construct(Di $dependencyInjectionContainer,
								Parser $commandHandlerParser) {

		$this->dependencyInjectionContainer = $dependencyInjectionContainer;
		$this->cachedCommandHandlerPipelines = new Map();
		$this->registeredCommandHandlerHandlers = new Set();

		$this->commandHandlerParser = $commandHandlerParser;
		$this->commandHandlerParser->setRegisteredCommandHandlerHandlers(
			$this->registeredCommandHandlerHandlers
		);
	}

	/**
	 * @param $handlerClassName
	 */
	public function registerCommandHandler($handlerClassName) {
		$this->registeredCommandHandlerHandlers->add($handlerClassName);
	}

	/**
	 * @param array $handlerClassNames
	 */
	public function setCommandHandlers(array $handlerClassNames) {
		foreach ($handlerClassNames as $handlerClassName) {
			$this->registeredCommandHandlerHandlers->add($handlerClassName);
		}
	}

	/**
	 * @param $command
	 * @return CommandHandler
	 */
	public function create($command) {
		if ($this->cachedCommandHandlerPipelines->hasKey($command) == false) {
			$commandHandlerPipeline = $this->commandHandlerPipelineFor(
				$this->theCommandHandlerForTheCommand($command)
			);

			$this->cachedCommandHandlerPipelines->add($command, $commandHandlerPipeline);
		}

		return $this->cachedCommandHandlerPipelines->get($command);
	}

	/**
	 * @param $command
	 * @return CommandHandler
	 */
	private function theCommandHandlerForTheCommand($command) {
		$handlerClassName = $this->commandHandlerParser->findTheHandlerClassNameForTheCommand($command);
		$commandHandler = $this->dependencyInjectionContainer->get($handlerClassName);
		return $commandHandler;
	}

	/**
	 * @param CommandHandler $commandHandler
	 * @return CommandHandler
	 */
	private function commandHandlerPipelineFor(CommandHandler $commandHandler) {
		$commandHandlerPipeline = $this->commandHandlerParser->commandHandlerPipeline(
			$commandHandler,
			$this->registeredCommandHandlerHandlers
		);

		while (!$commandHandlerPipeline->isEmpty()) {
			$precedingPipelineCommandHandler = $commandHandlerPipeline->pop();
			$commandHandler = new $precedingPipelineCommandHandler($commandHandler);
		}

		return $commandHandler;
	}
}