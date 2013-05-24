<?php

namespace TransparentCQRS\Command\Handler;

use TransparentCQRS\Command\Command;

abstract class CommandHandler {

	public function handle(Command $command) {
		$method = sprintf('handle%s', $this->commandNameWithoutNamespace($command));
		if (!method_exists($this, $method)) {
			throw new \BadMethodCallException(
				"There is no event named '$method' that can be applied to '" . get_class($this) . "'"
			);
		}

		$this->$method($command);
	}

	protected function buildTheExpectedHandleMethodName(Event $event) {
		$method = sprintf('apply%s', $this->commandNameWithoutNamespace($event));
		return $method;
	}

	private function commandNameWithoutNamespace($command) {
		$commandName = get_class($command);
		return substr($commandName, strrpos($commandName, "\\") + 1);
	}
	protected function throwAnErrorIfTheAggregateDoesNotSupportThisEvent($method) {
		if (!method_exists($this, $method)) {
			throw new \DomainException(
				"There is no event named '$method' that can be applied to '" . get_class($this) . "'"
			);
		}
	}

}

?>