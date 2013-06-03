<?php

namespace TransparentCQRS\Command\Handler;

use TransparentCQRS\Command\Command;

/**
 * Class CommandHandler
 * @package TransparentCQRS\Command\Handler
 */
abstract class CommandHandler {

	/**
	 * @param Command $command
	 * @return CommandHandler
	 */
	public abstract function handle(Command $command);
}

?>