<?php

namespace Queue\Service\Queue\Manager\Adapter\Mock;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Manager\Adapter\ConfigurationInterface;
use Queue\Service\Queue\Manager\Message\EncodedMessageCollection;

/**
 * Менеджер очередей / Конфигурация mock адаптера
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Adapter\Mock
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @var EncodedMessageCollection
     */
    private $messageCollection;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return AdapterInterface::TYPE_MOCK;
    }

    /**
     * Задать список сообщений
     *
     * @param EncodedMessageCollection $messageCollection
     * @return $this
     */
    public function setEncodedMessageCollection(EncodedMessageCollection $messageCollection)
    {
        $this->messageCollection = $messageCollection;
        return $this;
    }

    /**
     * Получить список сообщений
     *
     * @return EncodedMessageCollection
     */
    public function getEncodedMessageCollection()
    {
        return $this->messageCollection;
    }
}
