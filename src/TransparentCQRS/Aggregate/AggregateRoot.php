<?php
namespace TransparentCQRS\Aggregate;

use TransparentCQRS\Event\Event;

/**
 * Class AggregateRoot
 * @package TransparentCQRS\Aggregate
 */
abstract class AggregateRoot {
	/**
	 * @var array
	 */
	private $changes = array();

	/**
	 * @param Event $event
	 * @param bool $isNotLoadedFromHistory
	 */
	protected function applyEvent(Event $event, $isNotLoadedFromHistory = true) {
		$applyMethodName = $this->theExpectedApplyMethodName($event);
		$this->throwADomainExceptionIfTheAggregateDoesNotSupportThisEvent($applyMethodName);

		$this->$applyMethodName($event);

		if ($isNotLoadedFromHistory == true) {
			array_push($this->changes, $event);
		}
	}

	/**
	 * @param Event $event
	 * @return string
	 */
	private function theExpectedApplyMethodName(Event $event) {
		$method = sprintf('apply%s', $this->eventNameWithoutTheNamespace($event));
		return $method;
	}

	/**
	 * @param $method
	 * @throws \DomainException
	 */
	private function throwADomainExceptionIfTheAggregateDoesNotSupportThisEvent($method) {
		if (!method_exists($this, $method)) {
			throw new \DomainException(
				"There is no event named '$method' that can be applied to '" . get_class($this) . "'"
			);
		}
	}

	/**
	 * @param $event
	 * @return string
	 */
	private function eventNameWithoutTheNamespace($event) {
		$eventName = get_class($event);
		return substr($eventName, strrpos($eventName, "\\") + 1);
	}

	/**
	 * @param array $events
	 */
	public function loadFromHistory(array $events) {
		foreach ($events as $event) {
			$this->applyEvent($event, false);
		}
	}

	/**
	 * @return array
	 */
	public function getChanges() {
		return $this->changes;
	}
}