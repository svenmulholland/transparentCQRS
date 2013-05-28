<?php

namespace TransparentCQRS\Command\Handler;


use Builder\Command\CommandBuilder;
use Builder\Command\Handler\CommandHandlerBuilder;
use Builder\Command\Handler\SingleCommandHandler;
use Zend\Di\Di;

class ACommandHandler extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function invokesTheHandleMethodOfTheTargetedCommandHandler() {
		//$commandHandler = CommandHandlerBuilder::aSingleCommandHandler()->build();
	/*	$command = CommandBuilder::aCommandWithARegisteredHandler()->build();

		$commandHandlerBuilder = new \TransparentCQRS\Command\Handler\Builder\CommandHandlerBuilder(
			new  SingleCommandHandler()
		);
		$commandHandler = $commandHandlerBuilder->build();

		$commandHandler->handle($command);
	*/
	}

	/**
	 * @test
	 * @expectedException \DomainException
	public function throwsADomainExceptionIfNoHandlerIsRegisteredForTheGivenCommand() {
		$commandHandler = CommandHandlerBuilder::aSingleCommandHandler()->build();
		$command = CommandBuilder::aCommandWithoutARegisteredHandler()->build();

		$commandHandler->handle($command);
	} */

}