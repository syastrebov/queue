<?php

namespace Queue\Service\Queue\Parser\ArrayObject\Adapter;

use Queue\Service\Queue\Manager\Adapter\Rabbit\Configuration;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface as ManagerAdapterInterface;
use Queue\Service\Queue\Parser\AdapterInterface as ParserAdapterInterface;
use Queue\Service\Queue\Parser\ArrayObject\ArrayParamsTrait;

/**
 * Менеджер очередей / Парсер конфигурации rabbitMQ адаптера
 *
 * Class Rabbit
 * @package Queue\Service\Queue\Parser\ArrayObject\Adapter
 */
final class Rabbit implements ParserAdapterInterface
{
    use ArrayParamsTrait;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return ManagerAdapterInterface::TYPE_RABBIT;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $configuration = new Configuration();
        $configuration
            ->setHost($this->getRawParam('host'))
            ->setPort($this->getRawParam('port'))
            ->setVirtualHost($this->getRawParam('vhost'))
            ->setLogin($this->getRawParam('login'))
            ->setPassword($this->getRawParam('password'));

        return $configuration;
    }
}
