<?php

namespace Queue\Service\Queue\Manager\Exchange;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция обменников
 *
 * Class Collection
 * @package Queue\Service\Queue\Manager\Exchange
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить обменник
     *
     * @param ExchangeInterface $exchange
     * @return $this
     * @throws Exception
     */
    public function attach(ExchangeInterface $exchange)
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
     * @return ExchangeInterface|null
     * @throws Exception
     */
    public function getByName($type, $throwException = true)
    {
        foreach ($this->collection as $exchange) {
            /** @var ExchangeInterface $exchange */
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
