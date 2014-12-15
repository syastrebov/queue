<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Queue\Service\Queue\Manager\Exchange\Configuration;
use Queue\Service\Queue\Parser\ExchangeInterface;

/**
 * Менеджер очередей / Парсер конфигурации обменника
 *
 * Class Exchange
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
final class Exchange implements ArrayParamsInterface, ExchangeInterface
{
    use ArrayParamsTrait;

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return new Configuration($this->getRawParam('name'), $this->getRawParam('type'));
    }
}
