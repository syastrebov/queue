<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Queue\Service\Queue\Manager\Bind\Configuration;
use Queue\Service\Queue\Parser\BindInterface;
use Queue\Service\Queue\Parser\SetExchangeCollectionTrait;
use Queue\Service\Queue\Parser\SetQueueCollectionTrait;

/**
 * Менеджер очередей / Парсер конфигурации связи
 *
 * Class Bind
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
final class Bind implements ArrayParamsInterface, BindInterface
{
    use ArrayParamsTrait, SetQueueCollectionTrait, SetExchangeCollectionTrait;

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return new Configuration(
            $this->getExchangeCollection()->getByName($this->getRawParam('exchange')),
            $this->getQueueCollection()->getByName($this->getRawParam('queue')),
            $this->getRawParam('routingKey')
        );
    }
}
