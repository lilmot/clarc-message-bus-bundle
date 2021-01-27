<?php

/**
 * Interface: Bus event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Consumer\Event;

use ArtoxLab\AbstractBusEventMessage\V1\Events\EventInterface;

interface BusEventInterface extends EventInterface
{

    /**
     * Get message
     *
     * @return EventInterface|object
     */
    public function getMessageData();

    /**
     * Supports
     *
     * @param string $actionName Event action name
     *
     * @return boolean
     */
    public function supports(string $actionName): bool;

}
