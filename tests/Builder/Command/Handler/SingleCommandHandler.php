<?php

namespace Builder\Command\Handler;

use TransparentCQRS\Command\Command;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Annotations\Handles;
use TransparentCQRS\Command\Handler\Annotations\DecoratedBy;
use TransparentCQRS\Command\Handler\Annotations\IsCommandHandler;

/**
 * @IsCommandHandler
 */
class SingleCommandHandler extends CommandHandler {

	/**
	 * @Handles(command="Builder\Command\SingleCommand")
	 * @DecoratedBy({"Dumping"})
	 */
	public function handle(Command $command) {
		echo "single command handled";
	}
}