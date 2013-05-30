<?php

namespace Builder\Command\Handler;

use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Annotations\IsCommandHandler;

/**
 * @IsCommandHandler(name="Logging")
 */
class LoggingCommandHandler extends CommandHandler {

	public function __construct(CommandHandler $next) {
		$this->next = $next;
	}

	public function handle(Command $command) {
		echo "command handle logged\n";
		$this->next->handle($command);
	}
}