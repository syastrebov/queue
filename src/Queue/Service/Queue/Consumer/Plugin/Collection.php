<?php

namespace Queue\Service\Queue\Consumer\Plugin;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция плагинов обработки очереди
 *
 * Class Collection
 * @package Queue\Service\Queue\Consumer
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить плагин
     *
     * @param PluginInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(PluginInterface $plugin)
    {
        if (!$this->getByType($plugin->getType(), false)) {
            $this->collection[] = $plugin;
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
     * @return PluginInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var PluginInterface $plugin */
            if ($plugin->getType() === $type) {
                return $plugin;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}
