<?php

/**
 * Abstract bus event
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Consumer\Event;

use ArtoxLab\AbstractBusEventMessage\V1\BusMessageInterface;
use ArtoxLab\AbstractBusEventMessage\V1\Events\EventInterface;

abstract class BusEvent implements BusEventInterface
{

    /**
     * Bus event message
     *
     * @var BusMessageInterface
     */
    public $message;

    /**
     * Event
     *
     * @var EventInterface
     */
    public $event;

    /**
     * BusEvent constructor.
     *
     * @param BusMessageInterface $message EventMessage
     * @param EventInterface      $event   Event
     */
    public function __construct(BusMessageInterface $message, EventInterface $event)
    {
        $this->message = $message;
        $this->event   = $event;
    }

    /**
     * Get message
     *
     * @return EventInterface|object
     */
    public function getMessageData()
    {
        return $this->message->data;
    }

    /**
     * Supports
     *
     * @param string $actionName Event action name
     *
     * @return boolean
     */
    abstract public function supports(string $actionName): bool;

    /**
     * Attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->event->getAttributes();
    }

    /**
     * Change attributes
     *
     * @param array $props Attributes
     *
     * @return bool
     */
    public function setAttributes(array $props): bool
    {
        $this->event->setAttributes($props);
    }

    /**
     * Event name
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->event->getEventName();
    }

    /**
     * Event Id
     *
     * @return bool|string
     */
    public function getEventId()
    {
        return $this->event->getEventId();
    }

    /**
     * Change Event Id
     *
     * @param string $eventId Event Id
     *
     * @return void
     */
    public function setEventId(string $eventId): void
    {
        $this->event->setEventId($eventId);
    }

    /**
     * Previous attributes
     *
     * @return array
     */
    public function getPrevAttributes(): array
    {
        return $this->event->getPrevAttributes();
    }

    /**
     * Change previous attributes
     *
     * @param array $prevAttributes Previous attributes
     *
     * @return void
     */
    public function setPrevAttributes(array $prevAttributes = []): void
    {
        $this->event->setPrevAttributes($prevAttributes);
    }

    /**
     * Conversion to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->event;
    }

}
