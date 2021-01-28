<?php

/**
 * Deleted bus event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Consumer\Event;

use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventAction\EventActionInterface;

class BusDeletedEvent extends BusEvent implements BusDeletedEventInterface
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
        return $actionName === EventActionInterface::ACTION_DELETED;
    }

}
