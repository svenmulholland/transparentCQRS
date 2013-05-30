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
use Zend\ServiceManager\ServiceManager;

class TheInMemoryCommandBus extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		AnnotationRegistry::registerAutoloadNamespace("TransparentCQRS", __DIR__ . "/../../../../src");
	}
//
//	/**
//	 * @test
//	 */
//	public function triesToCreateACommandHandlerTroughTheCommandHandlerFactory() {
//		$command = CommandBuilder::aCommandWithoutARegisteredHandler()->build();
//$ch=\Mockery::mock("TransparentCQRS\Command\Command")
//	->shouldReceive("handle")
//	->once()
//	->with($command);
//
//		$commandHandlerFactory = \Mockery::mock("TransparentCQRS\Command\Handler\Factory\CommandHandlerFactory");
//		$commandHandlerFactory->shouldReceive("create")
//							  ->with($command)
//							  ->once()
//							  ->andReturn(
//								$ch
//							  );
//
//		$commandBus = new InMemoryCommandBus($commandHandlerFactory);
//
//
//		$commandBus->send($command);
//	}

	/**
	 * @test
	 */
	public function justATestWithoutAssertion() {
		$di = new Di();

		$commandHandlerFactory = new AnnotationAwareFactory(
			$di,
			new CommandHandlerReflector(
				new CachedReader(
					new AnnotationReader(),
					new ApcCache(),
					$debug = true
				)
			)
		);

		$commandHandlerFactory->registerCommandHandlers(
			array(
				"Builder\\Command\\Handler\\LoggingCommandHandler",
				"Builder\\Command\\Handler\\VarDumpCommandHandler",
				"Builder\\Command\\Handler\\SingleCommandHandler"
			)
		);

		$di->instanceManager()->addSharedInstance($commandHandlerFactory, "TransparentCQRS\Command\Handler\Factory\AnnotationAwareFactory");
		$di->instanceManager()->setTypePreference("TransparentCQRS\Command\Handler\Factory\CommandHandlerFactory", array("TransparentCQRS\Command\Handler\Factory\AnnotationAwareFactory"));

		$commandBus = $di->get("TransparentCQRS\Command\Bus\InMemoryCommandBus");

		$commandBus->send(
			new SingleCommand()
		);
	}
}
