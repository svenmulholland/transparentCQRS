<?php

namespace Builder\Command;


use TransparentCQRS\Command\Command;

class CommandBuilder {
	private $command;

	public static function aCommandWithARegisteredHandler() {
		$commandBuilder = new CommandBuilder(
			new CommandWithRegisteredHandler()
		);
		return $commandBuilder;
	}

	public static function aCommandWithoutARegisteredHandler() {
		$commandBuilder = new CommandBuilder(
			new CommandWithoutARegisteredHandler()
		);
		return $commandBuilder;
	}

	private function __construct(Command $command) {
		$this->command = $command;
	}

	public function build() {
		return $this->command;
	}
}