<?php

/**
 * Handler: created event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Consumer\Event\BusCreatedEventInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatedHandler implements MessageHandlerInterface
{

    /**
     * Message handler
     *
     * @param BusCreatedEventInterface $event Event
     *
     * @return void
     */
    public function __invoke(BusCreatedEventInterface $event): void
    {
        // ...
    }

}
