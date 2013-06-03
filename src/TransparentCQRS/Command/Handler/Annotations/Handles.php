<?php

namespace TransparentCQRS\Command\Handler\Annotations;


/**
 * @Annotation
 * @Target({"METHOD"})
 * @Attributes({
 * 		@Attribute("command", type = "string")
 * })
 */
class Handles {
	public $command;
}