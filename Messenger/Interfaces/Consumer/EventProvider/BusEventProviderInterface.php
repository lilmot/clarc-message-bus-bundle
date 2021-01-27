<?php

/**
 * Interface: Bus event provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Consumer\EventProvider;

interface BusEventProviderInterface
{

    /**
     * Get event class by action name
     *
     * @param string $actionName Event action name
     *
     * @return string|null
     */
    public function getEventClass(string $actionName): ?string;

}
