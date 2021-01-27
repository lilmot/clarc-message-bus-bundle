<?php

/**
 * Bus event provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Consumer\EventProvider;

class BusEventProvider implements BusEventProviderInterface
{

    /**
     * Bus events
     *
     * @var iterable
     */
    private $events;

    /**
     * BusEventProvider constructor.
     *
     * @param iterable $events Bus events
     */
    public function __construct(iterable $events)
    {
        $this->events = $events;
    }

    /**
     * Get event class by action name
     *
     * @param string $actionName Event action name
     *
     * @return string|null
     */
    public function getEventClass(string $actionName): ?string
    {
        foreach ($this->events as $event) {
            if (true === $event->supports($actionName)) {
                return get_class($event);
            }
        }

        return null;
    }

}
