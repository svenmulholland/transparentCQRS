<?php
namespace TransparentCQRS\Command\Bus;

use Builder\Command\CommandBuilder;

class TheInMemoryCommandBus extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 * @expectedException \DomainException
	 */
	public function throwsADomainExceptionIfNoHandlerIsRegisteredForTheGivenCommand() {
		$commandBus = new InMemoryCommandBus();
		$command = CommandBuilder::aCommandWithoutARegisteredHandler()->build();

		$commandBus->send($command);
	}
}
