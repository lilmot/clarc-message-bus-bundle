<?php

/**
 * Event message
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventMessage;

use ArtoxLab\AbstractBusEventMessage\V1\BusMessageFactoryInterface;
use ArtoxLab\AbstractBusEventMessage\V1\BusMessageInterface;
use ArtoxLab\AbstractBusEventMessage\V1\Events\EventInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Stamp\EventActionStamp;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;

class EventMessage implements EventMessageInterface
{

    /**
     * Bus message factory
     *
     * @var BusMessageFactoryInterface
     */
    private $busMessageFactory;

    /**
     * EventMessage constructor.
     *
     * @param BusMessageFactoryInterface $busMessageFactory Bus message factory
     */
    public function __construct(BusMessageFactoryInterface $busMessageFactory)
    {
        $this->busMessageFactory = $busMessageFactory;
    }

    /**
     * Make event message
     *
     * @param EventInterface $event      Event
     * @param string         $actionName Event action name
     *
     * @return Envelope
     */
    public function makeMessage(EventInterface $event, string $actionName): Envelope
    {
        return new Envelope($event, [new EventActionStamp($actionName)]);
    }

    /**
     * Make bus event message
     *
     * @param Envelope $envelope Envelope
     *
     * @return BusMessageInterface
     */
    public function makeBusMessage(Envelope $envelope): BusMessageInterface
    {
        $event       = $envelope->getMessage();
        $actionStamp = $envelope->last(EventActionStamp::class);

        if (null === $actionStamp) {
            throw new RuntimeException('EventActionStamp should not be empty');
        }

        $message         = $this->busMessageFactory->create();
        $message->action = $actionStamp->getActionName();
        $message->type   = $event->getEventName();
        $message->data   = $event;

        return $message;
    }

}
