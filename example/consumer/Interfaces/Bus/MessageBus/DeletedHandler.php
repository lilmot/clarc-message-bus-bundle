<?php

/**
 * Handler: deleted event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Consumer\Event\BusDeletedEventInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeletedHandler implements MessageHandlerInterface
{

    /**
     * Message handler
     *
     * @param BusDeletedEventInterface $event Event
     *
     * @return void
     */
    public function __invoke(BusDeletedEventInterface $event): void
    {
        // ...
    }
}
