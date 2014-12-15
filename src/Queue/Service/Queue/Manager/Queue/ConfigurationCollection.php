<?php

namespace Queue\Service\Queue\Manager\Queue;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция конфигураций очередей
 *
 * Class ConfigurationCollection
 * @package Queue\Service\Queue\Manager\Queue
 */
class ConfigurationCollection extends AbstractCollection
{
    /**
     * Добавить конфигурацию
     *
     * @param Configuration $config
     * @return $this
     * @throws Exception
     */
    public function attach(Configuration $config)
    {
        if (!$this->getByName($config->getName(), false)) {
            $this->collection[] = $config;
        } else {
            throw new Exception('Конфигурация уже был добавлена');
        }

        return $this;
    }

    /**
     * Получить обработчик по типу
     *
     * @param string $type
     * @param bool   $throwException
     *
     * @return Configuration|null
     * @throws Exception
     */
    public function getByName($type, $throwException = true)
    {
        foreach ($this->collection as $config) {
            /** @var Configuration $config */
            if ($config->getName() === $type) {
                return $config;
            }
        }
        if ($throwException) {
            throw new Exception('Конфигурация не найдена');
        }

        return null;
    }
}
