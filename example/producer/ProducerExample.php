<?php

/**
 * Example: Produce messages to message bus
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\Bus\MessageBus\MessageBusInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventAction\EventActionInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventMessage\EventMessageInterface;

class ProducerExample
{

    /**
     * Message bus
     *
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * Event message
     *
     * @var EventMessageInterface
     */
    private $eventMessage;

    /**
     * ProducerExample constructor.
     *
     * @param MessageBusInterface   $messageBus   Message bus
     * @param EventMessageInterface $eventMessage Event message
     */
    public function __construct(MessageBusInterface $messageBus, EventMessageInterface $eventMessage)
    {
        $this->messageBus   = $messageBus;
        $this->eventMessage = $eventMessage;
    }

    /**
     * Send message to message bus
     *
     * @return void
     */
    public function produce(): void
    {
        $event               = new YourLib\BusEventMessage\V1\Events\ExampleEvent();
        $event->example_id   = 1;
        $event->example_name = 'Name';

        $message = $this->eventMessage->makeMessage($event, EventActionInterface::ACTION_CREATED);

        $this->messageBus->publish($message);
    }

}
