<?php

/**
 * Handler: updated event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Consumer\Event\BusUpdatedEventInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdatedHandler implements MessageHandlerInterface
{

    /**
     * Message handler
     *
     * @param BusUpdatedEventInterface $event Event
     *
     * @return void
     */
    public function __invoke(BusUpdatedEventInterface $event): void
    {
        // ...
    }

}
