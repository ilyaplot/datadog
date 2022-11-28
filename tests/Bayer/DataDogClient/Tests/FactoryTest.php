<?php

namespace Bayer\DataDogClient\Tests;

use Bayer\DataDogClient\Factory;
use Bayer\DataDogClient\Event;
use Bayer\DataDogClient\Series\Metric;

class FactoryTest extends \PHPUnit_Framework_TestCase {
    public function testFactoryCanCreateMetric() {
        $metric = Factory::buildMetric(
            'test.metric.name',
            array(
                array(20),
                array(time() - 20, 20),
            ),
            array(
                'type' => Metric::TYPE_COUNTER,
                'host' => 'foo.bar.com',
                'tags' => array('foo' => 'bar')
            )
        );

        $this->assertEquals(Metric::TYPE_COUNTER, $metric->getType());
        $this->assertEquals('foo.bar.com', $metric->getHost());
        $this->assertEquals(array('foo' => 'bar'), $metric->getTags());

        $this->assertInstanceOf('Bayer\DataDogClient\Series\Metric', $metric);
    }

    public function testFactoryCanCreateEvent() {
        $event = Factory::buildEvent(
            'This is a dummy event',
            'My Event',
            array(
                'date_happened'    => 123456,
                'priority'         => Event::PRIORITY_LOW,
                'alert_type'       => Event::TYPE_SUCCESS,
                'source_type_name' => Event::SOURCE_BITBUCKET,
                'aggregationKey'   => 'foo.bar',
                'tags'             => array('foo' => 'bar')
            )
        );

        $this->assertEquals(123456, $event->getDateHappened());
        $this->assertEquals(Event::PRIORITY_LOW, $event->getPriority());
        $this->assertEquals(Event::TYPE_SUCCESS, $event->getAlertType());
        $this->assertEquals(Event::SOURCE_BITBUCKET, $event->getSourceTypeName());
        $this->assertEquals('foo.bar', $event->getAggregationKey());
        $this->assertEquals(array('foo' => 'bar'), $event->getTags());

        $this->assertInstanceOf('Bayer\DataDogClient\Event', $event);
    }

    /**
     * @expectedException \Bayer\DataDogClient\Factory\InvalidPropertyException
     */
    public function testInvalidOptionThrowsException() {
        Factory::buildEvent(
            'Dummy event',
            'My Event',
            array(
                'foo' => 'bar'
            )
        );
    }
}