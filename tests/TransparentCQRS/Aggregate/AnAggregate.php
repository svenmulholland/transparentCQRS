<?php
namespace TransparentCQRS\Aggregate;

use Builder\Aggregate\AggregateBuilder;
use Builder\Events\EventBuilder;

class AnAggregate extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function doestNotIncludeAnEventLoadedFromHistoryInTheActualChangeSet() {
		$aggregateRoot = AggregateBuilder::anAggregate()->build();
		$event = EventBuilder::anSupportedEvent()->build();

		$aggregateRoot->loadFromHistory(
			array($event)
		);

		assertThat($aggregateRoot->getChanges(), not(contains($event)));
	}

	/**
	 * @test
	 */
	public function includesAllAppliedEventsInTheActualChangeSet() {
		$aggregateRoot = AggregateBuilder::anAggregate()->build();

		$aggregateRoot->doSomething();

		assertThat($aggregateRoot->getChanges(), contains(anInstanceOf("Builder\\Events\\SupportedEvent")));
	}

	/**
	 * @test
	 * @expectedException \DomainException
	 */
	public function throwsADomainExceptionIfTheAggregateDoestNotSupportTheProvidedEvent() {
		$aggregateRoot = AggregateBuilder::anAggregate()->build();
		$event = EventBuilder::anUnsupportedEvent()->build();

		$aggregateRoot->loadFromHistory(
			array($event)
		);

		assertThat($aggregateRoot->getChanges(), not(contains($event)));
	}
}
