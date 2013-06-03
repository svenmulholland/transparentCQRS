<?php
namespace TransparentCQRS\Command\Bus;

use Builder\Command\CommandBuilder;
use Builder\Command\SingleCommand;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Icecave\Collections\Set;
use TransparentCQRS\Utilities\Annotation\CommandHandler\Parser;
use Zend\Di\Config;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceManager;

class TheInMemoryCommandBus extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		AnnotationRegistry::registerAutoloadNamespace("TransparentCQRS", __DIR__ . "/../../../../src");
	}

	/**
	 * @test
	 */
	public function triesToCreateACommandHandlerTroughTheCommandHandlerFactory() {
		$command = CommandBuilder::aCommandWithoutARegisteredHandler()->build();
		$commandHandler = \Mockery::mock("TransparentCQRS\Command\Command")->shouldIgnoreMissing();

		$commandHandlerFactory = \Mockery::mock("TransparentCQRS\Command\Handler\Factories\CommandHandlerFactory");
		$commandHandlerFactory->shouldReceive("create")
			->with($command)
			->once()
			->andReturn(
				$commandHandler
			);

		$commandBus = new InMemoryCommandBus($commandHandlerFactory);

		$commandBus->send($command);
	}

	/**
	 * @test
	 */
	public function justATestWithoutAssertion() {
		$di = new Di();
		$diConfiguration = new Config(
			array(
				'instance' => array(
					'preference' => array(
						'TransparentCQRS\Command\Handler\Factories\CommandHandlerFactory' => 'TransparentCQRS\Command\Handler\Factories\AnnotationAwareFactory',
						'Doctrine\Common\Annotations\Reader' => 'Doctrine\Common\Annotations\AnnotationReader'
					),
					'TransparentCQRS\Command\Handler\Factories\AnnotationAwareFactory' => array(
						'injections' => array(
							'setCommandHandlers' => array(
								'handlerClassNames' => array(
									"Builder\\Command\\Handler\\LoggingCommandHandler",
									"Builder\\Command\\Handler\\VarDumpCommandHandler",
									"Builder\\Command\\Handler\\SingleCommandHandler"
								)
							)
						)
					)
				)
			)
		);

		$diConfiguration->configure($di);

		$commandBus = $di->get("TransparentCQRS\Command\Bus\InMemoryCommandBus");

		$commandBus->send(
			new SingleCommand()
		);
	}
}
