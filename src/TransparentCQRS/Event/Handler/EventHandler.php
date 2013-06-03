<?php

namespace TransparentCQRS\Event\Handler;


use TransparentCQRS\Event\Event;

interface EventHandler {
	public function handle(Event $event);
}