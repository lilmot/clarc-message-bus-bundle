<?php

/**
 * Updated bus event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Consumer\Event;

use ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Producer\EventAction\EventActionInterface;

class BusUpdatedEvent extends BusEvent implements BusUpdatedEventInterface
{

    /**
     * Supports
     *
     * @param string $actionName Event action name
     *
     * @return boolean
     */
    public function supports(string $actionName): bool
    {
        return $actionName === EventActionInterface::ACTION_UPDATED;
    }

}
