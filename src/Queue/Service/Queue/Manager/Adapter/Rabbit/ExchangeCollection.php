<?php

namespace Queue\Service\Queue\Manager\Adapter\Rabbit;

use BmCommon\Collection\AbstractCollection;
use AMQPExchange;
use Exception;

/**
 * Менеджер очередей / Коллекция экземпляров AMQP обменников
 *
 * Class ExchangeCollection
 * @package Queue\Service\Queue\Manager\Adapter\Rabbit
 */
class ExchangeCollection extends AbstractCollection
{
    /**
     * Добавить обменник
     *
     * @param AMQPExchange $exchange
     * @return $this
     * @throws Exception
     */
    public function attach(AMQPExchange $exchange)
    {
        if (!$this->getByName($exchange->getName(), false)) {
            $this->collection[] = $exchange;
        } else {
            throw new Exception('Обработчик уже был добавлен');
        }

        return $this;
    }

    /**
     * Получить обработчик по типу
     *
     * @param string $type
     * @param bool   $throwException
     *
     * @return AMQPExchange|null
     * @throws Exception
     */
    public function getByName($type, $throwException = true)
    {
        foreach ($this->collection as $exchange) {
            /** @var AMQPExchange $exchange */
            if ($exchange->getName() === $type) {
                return $exchange;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}
