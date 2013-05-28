<?php

namespace Builder\Command\Handler;


use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Annotations\Dumping;

/**
 * @Dumping
 */
class VarDumpCommandHandler extends CommandHandler{

	public function __construct(CommandHandler $next) {
		$this->next = $next;
	}

	public function handle(Command $command) {
		var_dump($command);
		$this->next->handle($command);
	}
}