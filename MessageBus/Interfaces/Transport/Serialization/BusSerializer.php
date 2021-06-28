<?php

/**
 * Bus serializer
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Transport\Serialization;

use ArtoxLab\AbstractBusEventMessage\V1\BusMessageFactoryInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Consumer\EventProvider\BusEventProviderInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Producer\EventMessage\EventMessageInterface;
use ArtoxLab\Bundle\ClarcMessageBusBundle\MessageBus\Interfaces\Stamp\EventActionStamp;
use InvalidArgumentException;
use JsonException;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class BusSerializer implements SerializerInterface
{

    /**
     * Message bus event provider
     *
     * @var BusEventProviderInterface
     */
    private $eventProvider;

    /**
     * Event message
     *
     * @var EventMessageInterface
     */
    private $eventMessage;

    /**
     * Bus message factory
     *
     * @var BusMessageFactoryInterface
     */
    private $busMessageFactory;

    /**
     * BusSerializer constructor.
     *
     * @param BusEventProviderInterface  $eventProvider     Message bus event provider
     * @param EventMessageInterface      $eventMessage      Event message
     * @param BusMessageFactoryInterface $busMessageFactory Bus message factory
     */
    public function __construct(
        BusEventProviderInterface $eventProvider,
        EventMessageInterface $eventMessage,
        BusMessageFactoryInterface $busMessageFactory
    ) {
        $this->eventProvider     = $eventProvider;
        $this->eventMessage      = $eventMessage;
        $this->busMessageFactory = $busMessageFactory;
    }

    /**
     * Decodes an envelope and its message from an encoded-form.
     *
     * The `$encodedEnvelope` parameter is a key-value array that
     * describes the envelope and its content, that will be used by the different transports.
     *
     * The most common keys are:
     * - `body` (string) - the message body
     * - `headers` (string<string>) - a key/value pair of headers
     *
     * @param array $encodedEnvelope Envelope
     *
     * @throws JsonException
     *
     * @return Envelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        try {
            $messageBody = json_decode($encodedEnvelope['body'], true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new MessageDecodingFailedException('Encoded envelope should be a valid json');
        }

        if (false === isset($messageBody['action'])) {
            throw new MessageDecodingFailedException('Message should have "action" property');
        }

        $eventClass = $this->eventProvider->getEventClass($messageBody['action']);

        if (null === $eventClass) {
            throw new MessageDecodingFailedException("Event class not found for action \"{$messageBody['action']}\"");
        }

        try {
            $message = $this->busMessageFactory->create((string) $encodedEnvelope['body']);
        } catch (InvalidArgumentException $exception) {
            throw new MessageDecodingFailedException("Invalid message: " . $exception->getMessage());
        }

        return new Envelope(
            (new $eventClass($message, $message->data)),
            [$this->getRedeliveryStamp($encodedEnvelope)]
        );
    }

    /**
     * Returns RedeliveryStamp object
     *
     * @param array $encodedEnvelope Envelope
     *
     * @return RedeliveryStamp
     */
    protected function getRedeliveryStamp(array $encodedEnvelope): RedeliveryStamp
    {
        return new RedeliveryStamp(($encodedEnvelope['headers']['retry'] ?? 0));
    }

    /**
     * Encodes an envelope content (message & stamps) to a common format understandable by transports.
     * The encoded array should only contain scalars and arrays.
     *
     * Stamps that implement NonSendableStampInterface should
     * not be encoded.
     *
     * The most common keys of the encoded array are:
     * - `body` (string) - the message body
     * - `headers` (string<string>) - a key/value pair of headers
     *
     * @param Envelope $envelope Envelope
     *
     * @throws RuntimeException
     *
     * @return array
     */
    public function encode(Envelope $envelope): array
    {
        $stamp = $envelope->last(RedeliveryStamp::class);

        if (null === $stamp
            || false === ($stamp instanceof RedeliveryStamp)
        ) {
            throw new RuntimeException('RedeliveryStamp should not be empty');
        }

        $actionStamp = $envelope->last(EventActionStamp::class);
        $message     = $envelope->getMessage();

        if (null === $actionStamp
            && true === isset($message->message->action)
        ) {
            $envelope = $envelope->with(new EventActionStamp($message->message->action));
        }

        return [
            'headers' => ['retry' => $stamp->getRetryCount()],
            'body'    => $this->eventMessage->makeBusMessage($envelope),
        ];
    }

}
