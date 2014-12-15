<?php

namespace Queue\Service\Queue\Event\Parser;
use Queue\Service\Queue\Event\EventInterface;

/**
 * Менеджер очередей / Сервис парсера события
 *
 * Class Service
 * @package Queue\Service\Queue\Event\Parser
 */
class Service implements ParserInterface
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * Constructor
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Добавить парсер
     *
     * @param ParserPluginInterface $parser
     * @return $this
     */
    public function attach(ParserPluginInterface $parser)
    {
        $this->collection->attach($parser);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(EventInterface $event)
    {
        return $this->collection->getByType($event->getType())->toArray($event);
    }

    /**
     * {@inheritdoc}
     */
    public function toEvent(array $event)
    {
        if (!isset($event['type'])) {
            throw new Exception('Неизвестный тип сообщения');
        }

        return $this->collection->getByType($event['type'])->toEvent($event);
    }
}
