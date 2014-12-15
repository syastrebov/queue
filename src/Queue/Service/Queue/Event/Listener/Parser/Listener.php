<?php

namespace Queue\Service\Queue\Event\Listener\Parser;

use Zend\EventManager\EventInterface as ZendEventInterface;
use Queue\Service\Queue\Event\EventInterface as QueueEventInterface;
use Queue\Service\Queue\Event\Listener\AbstractListener;
use Queue\Service\Queue\Event\Parser;
use Queue\Service\Queue\Manager\Decoder\DecoderInterface;
use Queue\Service\Queue\Manager\ManagerInterface;

/**
 * Менеджер очередей / Zf2 event manager слушатель входящих событий
 * Отправляет сообщения в очередь в json формате
 *
 * Class ParserListener
 * @package Queue\Service\Queue\Event\Listener\Parser
 */
final class Listener extends AbstractListener
{
    /**
     * @var Parser\Service
     */
    private $parser;

    /**
     * Constructor
     *
     * @param ManagerInterface       $queueManager
     * @param Parser\ParserInterface $parser
     */
    public function __construct(ManagerInterface $queueManager, Parser\ParserInterface $parser)
    {
        parent::__construct($queueManager);
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return QueueEventInterface::TYPE_QUEUE_PARSER_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ZendEventInterface $event)
    {
        $event = $this->getQueueEvent($event);
        $this->queueManager
            ->init()
            ->getExchange($event->getExchangeName())
            ->publish(
                $event->getRoutingKey(),
                $this->parser->toArray($event),
                DecoderInterface::TYPE_JSON,
                $event->getAttemptCount(),
                $event->getPriority()
            );
    }
}
