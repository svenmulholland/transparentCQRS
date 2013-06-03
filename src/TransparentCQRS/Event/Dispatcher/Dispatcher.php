<?php

namespace TransparentCQRS\Event\Dispatcher;


use TransparentCQRS\Event\Event;

interface Dispatcher {
	public function dispatch(Event $event);
}