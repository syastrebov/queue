<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Queue\Service\Queue\Manager\Queue\Configuration;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Parser\QueueInterface;
use Queue\Service\Queue\Parser\SetExchangeCollectionTrait;

/**
 * Менеджер очередей / Парсер конфигурации очереди
 *
 * Class Queue
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
final class Queue implements ArrayParamsInterface, QueueInterface
{
    use ArrayParamsTrait, SetExchangeCollectionTrait;

    /**
     * @var Bind
     */
    private $bindParser;

    /**
     * Constructor
     *
     * @param Bind $bindParser
     */
    public function __construct(Bind $bindParser)
    {
        $this->bindParser = $bindParser;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        // Базовая конфигурация
        $configuration = new Configuration(
            $this->getRawParam('name'),
            $this->getRawParam('ttl')
        );

        // Редирект по таймауту
        if ($this->hasRawParam('ttlRoute')) {
            $this->bindParser->setRawConfig($this->getRawParam('ttlRoute'));
            $configuration->setTimeoutRoute(
                $this->getExchangeCollection()->getByName($this->bindParser->getRawParam('exchange')),
                $this->bindParser->getRawParam('routingKey')
            );
        }

        // Редирект при ошибке в обработке в консьюмере
        if ($this->hasRawParam('rejectRoute')) {
            $this->bindParser->setRawConfig($this->getRawParam('rejectRoute'));
            $configuration
                ->setRejectRoute(
                    $this->getExchangeCollection()->getByName($this->bindParser->getRawParam('exchange')),
                    $this->bindParser->getRawParam('routingKey')
                );
        }

        // Очередь с приоритетами
        if ($this->hasRawParam('priority')) {
            $configuration->setMaxPriority($this->getRawParam('priority'));
        }

        return $configuration;
    }
}
