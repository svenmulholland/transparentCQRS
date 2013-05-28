<?php
namespace TransparentCQRS\Aggregate;

use TransparentCQRS\Event\Event;

abstract class AggregateRoot {
	private $changes = array();

	protected function applyEvent(Event $event, $isNotLoadedFromHistory = true) {
		$applyMethodName = $this->theExpectedApplyMethodName($event);
		$this->throwADomainExceptionIfTheAggregateDoesNotSupportThisEvent($applyMethodName);

		$this->$applyMethodName($event);

		if ($isNotLoadedFromHistory == true) {
			array_push($this->changes, $event);
		}
	}

	private function theExpectedApplyMethodName(Event $event) {
		$method = sprintf('apply%s', $this->eventNameWithoutTheNamespace($event));
		return $method;
	}

	private function throwADomainExceptionIfTheAggregateDoesNotSupportThisEvent($method) {
		if (!method_exists($this, $method)) {
			throw new \DomainException(
				"There is no event named '$method' that can be applied to '" . get_class($this) . "'"
			);
		}
	}

	private function eventNameWithoutTheNamespace($event) {
		$eventName = get_class($event);
		return substr($eventName, strrpos($eventName, "\\") + 1);
	}

	public function loadFromHistory(array $events) {
		foreach ($events as $event) {
			$this->applyEvent($event, false);
		}
	}

	public function getChanges() {
		return $this->changes;
	}
}