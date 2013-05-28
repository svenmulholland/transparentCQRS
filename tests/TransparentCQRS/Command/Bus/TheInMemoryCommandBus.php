<?php
namespace TransparentCQRS\Command\Bus;

use Builder\Command\CommandBuilder;
use Builder\Command\SingleCommand;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ApcCache;
use TransparentCQRS\Command\Handler\Factory\AnnotationAwareFactory;
use TransparentCQRS\Command\Handler\Reflection\CommandHandlerReflector;
use Zend\Di\Di;

class TheInMemoryCommandBus extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		AnnotationRegistry::registerAutoloadNamespace("TransparentCQRS", __DIR__ . "/../../../../src");
	}

	/**
	 * @test
	 * @expectedException \DomainException
	 */
	public function throwsADomainExceptionIfNoHandlerIsRegisteredForTheGivenCommand() {
		$commandHandlerFactory = new AnnotationAwareFactory(
			new Di(),
			new CommandHandlerReflector(
				new CachedReader(
					new AnnotationReader(),
					new ApcCache(),
					$debug = true
				)
			)
		);

		$commandBus = new InMemoryCommandBus($commandHandlerFactory);
		$command = CommandBuilder::aCommandWithoutARegisteredHandler()->build();

		$commandBus->send($command);
	}

	/**
	 * @test
	 */
	public function annot() {

		$commandHandlerFactory = new AnnotationAwareFactory(
			new Di(),
			new CommandHandlerReflector(
				new CachedReader(
					new AnnotationReader(),
					new ApcCache(),
					$debug = true
				)
			)
		);

		$commandHandlerFactory->registerCommandHandler("Builder\\Command\\Handler\\LoggingCommandHandler");
		$commandHandlerFactory->registerCommandHandler("Builder\\Command\\Handler\\VarDumpCommandHandler");
		$commandHandlerFactory->registerCommandHandler("Builder\\Command\\Handler\\SingleCommandHandler");

		$commandBus = new InMemoryCommandBus($commandHandlerFactory);

		$commandBus->send(
			new SingleCommand()
		);
	}
}
