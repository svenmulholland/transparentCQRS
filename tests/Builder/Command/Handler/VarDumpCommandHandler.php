<?php

namespace Builder\Command\Handler;


use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Annotations\IsCommandHandler;
use TransparentCQRS\Command\Handler\Annotations\DecoratedBy;
/**
 * @IsCommandHandler(name="Dumping")
 */
class VarDumpCommandHandler extends CommandHandler{

	public function __construct(CommandHandler $next) {
		$this->next = $next;
	}

	/**
	 * @DecoratedBy({"Logging"})
	 */
	public function handle(Command $command) {
		var_dump($command);
		$this->next->handle($command);
	}
}