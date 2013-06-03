<?php

namespace TransparentCQRS\Command\Handler\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class DecoratedBy {
	/** @var array */
	public $decorations;
}