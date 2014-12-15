<?php

namespace Queue\Service\Queue\Manager\Exchange;

use Exception;

/**
 * Менеджер очередей / Конфигурация обменника
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Exchange
 */
final class Configuration
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $type
     *
     * @throws Exception
     */
    public function __construct($name, $type)
    {
        if (!in_array($type, $this->getTypes(), true)) {
            throw new Exception(sprintf('Задан неверный тип обменника `%s`', $type));
        }

        $this->name = (string)$name;
        $this->type = (string)$type;
    }

    /**
     * Название обменника
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Тип обменника
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Доступные типы
     *
     * @return array
     */
    public function getTypes()
    {
        return [
            ExchangeInterface::TYPE_DIRECT,
            ExchangeInterface::TYPE_TOPIC,
            ExchangeInterface::TYPE_FANOUT,
        ];
    }
}
