<?php

/**
 * Interface: Event message
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventMessage;

use ArtoxLab\AbstractBusEventMessage\V1\BusMessageInterface;
use ArtoxLab\AbstractBusEventMessage\V1\Events\EventInterface;
use Symfony\Component\Messenger\Envelope;

interface EventMessageInterface
{

    /**
     * Make event message
     *
     * @param EventInterface $event      Event
     * @param string         $actionName Event action name
     *
     * @return Envelope
     */
    public function makeMessage(EventInterface $event, string $actionName): Envelope;

    /**
     * Make bus event message
     *
     * @param Envelope $envelope Envelope
     *
     * @return BusMessageInterface
     */
    public function makeBusMessage(Envelope $envelope): BusMessageInterface;

}
