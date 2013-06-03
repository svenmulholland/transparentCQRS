<?php

namespace TransparentCQRS\Command\Handler\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 *  * @Attributes({
 * 		@Attribute("name", type = "string")
 * })
 */
class IsCommandHandler {
	public $name;
}