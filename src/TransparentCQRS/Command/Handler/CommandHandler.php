<?php

namespace TransparentCQRS\Command\Handler;

use TransparentCQRS\Command\Command;

abstract class CommandHandler {
	public abstract function handle(Command $command);
}

?>