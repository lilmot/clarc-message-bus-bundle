<?php

/**
 * Stamp: Action name for bus event
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\Messenger\Interfaces\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class EventActionStamp implements StampInterface
{

    /**
     * Event action name
     *
     * @var string
     */
    private $actionName;

    /**
     * EventActionStamp constructor.
     *
     * @param string $actionName Event action name
     */
    public function __construct(string $actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * Returns event action name
     *
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

}
