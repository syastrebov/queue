<?php

namespace Queue\Service\Queue\Manager\Adapter\Rabbit;

use Queue\Entity\Message;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Manager\Message\EncodedMessage;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;
use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;

/**
 * Менеджер очередей / Адаптер для rabbitMQ
 *
 * Class Adapter
 * @package Queue\Service\Queue\Manager\Adapter\Rabbit
 */
final class Adapter implements AdapterInterface
{
    /**
     * @var QueueCollection
     */
    private $queueCollection;

    /**
     * @var ExchangeCollection
     */
    private $exchangeCollection;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * Constructor
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $connection = new AMQPConnection($this->config->toAMQPConnectionParams());
        $connection->pconnect();

        $this->channel            = new AMQPChannel($connection);
        $this->queueCollection    = new QueueCollection();
        $this->exchangeCollection = new ExchangeCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function publish(
        Exchange\Configuration $configuration,
        $routingKey,
        $message,
        $contentType,
        $attempt,
        $priority
    ) {
        return $this->declareExchange($configuration)->publish($message, $routingKey, AMQP_NOPARAM, [
            'content_type' => $contentType,
            'priority'     => $priority,
            'headers'      => [
                'attempt'  => $attempt,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Queue\Configuration $configuration)
    {
        $envelope = $this->declareQueue($configuration)->get();
        if ($envelope) {
            return new EncodedMessage(
                new Message(
                    $envelope->getDeliveryTag(),
                    $envelope->getBody(),
                    $envelope->getHeader('attempt')
                ),
                $envelope->getContentType()
            );
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(Exchange\Configuration $exchangeConfig, Queue\Configuration $queueConfig, $routingKey)
    {
        return $this
            ->declareQueue($queueConfig)
            ->bind($this->declareExchange($exchangeConfig)->getName(), $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function ack(Queue\Configuration $configuration, $id)
    {
        return $this->declareQueue($configuration)->ack($id);
    }

    /**
     * {@inheritdoc}
     */
    public function nack(Queue\Configuration $configuration, $id)
    {
        return $this->declareQueue($configuration)->nack($id, AMQP_REQUEUE);
    }

    /**
     * Объявить обменник
     *
     * @param Exchange\Configuration $configuration
     * @return AMQPExchange
     */
    private function declareExchange(Exchange\Configuration $configuration)
    {
        $exchange = $this->exchangeCollection->getByName($configuration->getName(), false);
        if (!$exchange) {
            $exchange = new AMQPExchange($this->channel);
            $exchange->setName($configuration->getName());
            $exchange->setType($configuration->getType());
            $exchange->declareExchange();

            $this->exchangeCollection->attach($exchange);
        }

        return $exchange;
    }

    /**
     * Объявить очередь
     *
     * @param Queue\Configuration $configuration
     * @return AMQPQueue
     */
    private function declareQueue(Queue\Configuration $configuration)
    {
        $queue = $this->queueCollection->getByName($configuration->getName(), false);
        if (!$queue) {
            $attributes = [];
            if ($configuration->getTimeout() > 0) {
                $attributes = array_merge($attributes, [
                    'x-message-ttl' => $configuration->getMicroTimeout(),
                ]);
                if ($configuration->getTimeoutRoute()) {
                    $routeConfig = $configuration->getTimeoutRoute();
                    $attributes  = array_merge($attributes, [
                        'x-dead-letter-exchange'    => $routeConfig->getExchangeConfiguration()->getName(),
                        'x-dead-letter-routing-key' => $routeConfig->getRoutingKey(),
                    ]);
                }
            }
            if ($configuration->getMaxPriority() > 0) {
                $attributes  = array_merge($attributes, [
                    'x-max-priority' => $configuration->getMaxPriority(),
                ]);
            }

            $queue = new AMQPQueue($this->channel);
            $queue->setName($configuration->getName());
            $queue->setFlags(AMQP_DURABLE);
            $queue->setArguments($attributes);
            $queue->declareQueue();

            $this->queueCollection->attach($queue);
        }

        return $queue;
    }
}
