<?php

namespace Queue\Service\Queue\Parser;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция парсеров конфигурации адаптеров
 *
 * Class AdapterCollection
 * @package Queue\Service\Queue\Parser
 */
class AdapterCollection extends AbstractCollection
{
    /**
     * Добавить парсер конфигурации адаптера
     *
     * @param AdapterInterface $adapter
     * @return $this
     * @throws Exception
     */
    public function attach(AdapterInterface $adapter)
    {
        if (!$this->getByType($adapter->getType(), false)) {
            $this->collection[] = $adapter;
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
     * @return AdapterInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $adapter) {
            /** @var AdapterInterface $adapter */
            if ($adapter->getType() === $type) {
                return $adapter;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}
