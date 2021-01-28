<?php

/**
 * Middleware: Add redelivery stamp to message
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;

class AddRedeliveryStampMiddleware implements MiddlewareInterface
{

    /**
     * Retry count for the message to be redelivered
     *
     * @var integer
     */
    private $retryCount;

    /**
     * AddRedeliveryStampMiddleware constructor.
     *
     * @param int $retryCount Retry count
     */
    public function __construct(int $retryCount)
    {
        $this->retryCount = $retryCount;
    }

    /**
     * Handle
     *
     * @param Envelope       $envelope Envelope
     * @param StackInterface $stack    Stack
     *
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(RedeliveryStamp::class)) {
            $envelope = $envelope->with(new RedeliveryStamp($this->retryCount));
        }

        return $stack->next()->handle($envelope, $stack);
    }

}
