# Clean Architecture message bus bundle

# Installation

## Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```bash
$ composer require artox-lab/clarc-message-bus-bundle
```

## Applications that don't use Symfony Flex

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this bundle:

```bash
$ composer require artox-lab/clarc-message-bus-bundle
```

This command requires you to have Composer installed globally, as explained in
the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    ArtoxLab\Bundle\ClarcMessageBusBundle\ArtoxLabClarcMessageBusBundle::class => ['all' => true],
];
```

### Step 3: Configuration

Configure bundle

```yaml
# config/packages/artox_lab_clarc_message_bus.yaml

artox_lab_clarc_message_bus:
  bus:
    middleware:
      add_redelivery_stamp_middleware:
        retry_count: 10

```

# Usage

### Step 1: Create your event lib based on [abstract-bus-event-message](https://github.com/artox-lab/abstract-bus-event-message)

Add your event lib in project via composer

### Step 2: Configuring Services in the Container

Bundle uses [abstract-bus-event-message](https://github.com/artox-lab/abstract-bus-event-message) as dependency. You
need to configure the services for the interfaces:

```yaml
# config/services.yaml

app_message_bus.producer.bus_event_message_factory:
  class: YourLib\BusEventMessage\V1\BusMessageFactory

ArtoxLab\AbstractBusEventMessage\V1\BusMessageFactoryInterface: '@app_message_bus.producer.bus_event_message_factory'

app_message_bus.producer.bus_event_message:
  class: YourLib\BusEventMessage\V1\BusMessage

ArtoxLab\AbstractBusEventMessage\V1\BusMessageInterface: '@app_message_bus.producer.bus_event_message'

app_message_bus.producer.bus_event:
  class: YourLib\BusEventMessage\V1\Events\BaseEvent

ArtoxLab\AbstractBusEventMessage\V1\Events\EventInterface: '@app_message_bus.producer.bus_event'
```

### Step 3: Configure symfony/messenger

```yaml
# config/packages/messenger.yaml

framework:
  messenger:
    buses:
      message.bus:
        middleware:
          - validation
          - artox_lab_clarc_message_bus.middleware.add_redelivery_stamp_middleware

    transports:
      example:
        dsn: "%env(RABBITMQ_TRANSPORT_DSN)%"
        serializer: artox_lab_clarc_message_bus.transport.bus_serializer
        retry_strategy:
          delay: 10000
          max_retries: 10
          multiplier: 1
        options:
          exchange:
            name: "events.example"
            type: "topic"
            flags: 4
          queues:
            events:
              binding_keys: [ '#' ]

    routing:
      'YourLib\BusEventMessage\V1\Events\ExampleEvent': [example]
```

#### Exchange type "topic"

If exchange type "topic" then symfony don't create queues automatically.
Configure [RabbitMqBundle](https://github.com/artox-lab/RabbitMqBundle)

```yaml
# config/packages/old_sound_rabbit_mq.yaml

old_sound_rabbit_mq:
  connections:
    default:
      url: '%env(RABBITMQ_TRANSPORT_DSN)%'

  producers:
    example:
      connection:       default
      exchange_options: {name: 'events.example', type: topic, auto_delete: true, durable: true}
      service_alias:    example
```

Open a command console, enter your project directory and execute the following commands to create queues and setup transports:

```bash
$ php bin/console rabbitmq:setup-fabric
$ php bin/console messenger:setup-transports
```

# Examples

* [Consume messages](example/consumer)
* [Produce messages](example/producer)
