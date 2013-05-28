<?php

namespace Builder\Command\Handler;


use Builder\Command\SingleCommand;
use TransparentCQRS\Command\Handler\CommandHandler;
use TransparentCQRS\Command\Handler\Annotations\Logging;
use TransparentCQRS\Command\Handler\Annotations\Dumping;

class SingleCommandHandler extends CommandHandler {

	/**
	 * @TransparentCQRS\Command\Handler\Annotations\Logging
	 * @TransparentCQRS\Command\Handler\Annotations\Dumping
	 */
	public function handle(SingleCommand $command) {
		echo "single command handled";
	}
}