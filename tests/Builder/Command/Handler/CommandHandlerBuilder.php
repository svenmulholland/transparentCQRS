<?php

namespace Builder\Command\Handler;


use TransparentCQRS\Command\Handler\CommandHandler;

class CommandHandlerBuilder {
	private $commandHandler;

	public static function aSingleCommandHandler() {
		$commandHandlerBuilder = new CommandHandlerBuilder(
			new LoggingCommandHandler(
				new SingleCommandHandler()
			)
		);
		return $commandHandlerBuilder;
	}

	private function __construct(CommandHandler $commandHandler) {
		$this->commandHandler = $commandHandler;
	}

	public function build() {
		return $this->commandHandler;
	}
}