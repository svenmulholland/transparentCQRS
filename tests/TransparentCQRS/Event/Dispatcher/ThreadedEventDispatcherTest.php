<?php
namespace TransparentCQRS\Event\Dispatcher;

use Aza\Components\Thread\ThreadPool;
use Builder\Events\SupportedEvent;
use Builder\Events\SupportedEventHandler;
use TransparentCQRS\Event\Dispatcher\Threaded\ThreadedEventDispatcher;
use Zend\Di\Config;
use Zend\Di\Di;

class ThreadedEventDispatcherTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function threadTest() {
		$di = new Di();
		$diConfiguration = new Config(
			array(
				'definition' => array(
					'class' => array(
						'Aza\Components\Thread\ThreadPool' => array(
						'__construct' => array(
								'threadName'  => array(
									'required' => false,
									'type'     => false
								),
								'maxThreads' => array(
									'required' => false,
									'type' => false
								)
							)
						)
					)
				),
				'instance' => array(
					'Aza\Components\Thread\ThreadPool' => array(
						'parameters' => array(
							'threadName' => 'TransparentCQRS\Event\Dispatcher\Threaded\EventHandlerThread',
							'maxThreads' => 2
							)
						)
					)
				)
		);

		$diConfiguration->configure($di);
		/** @var ThreadedEventDispatcher $eventDispatcher */
		$eventDispatcher = $di->get("TransparentCQRS\Event\Dispatcher\Threaded\ThreadedEventDispatcher");

		$eventDispatcher->registerEventHandler(
			new SupportedEventHandler()
		);

		$eventDispatcher->dispatch(new SupportedEvent());
		$eventDispatcher->dispatch(new SupportedEvent());
	}
}
