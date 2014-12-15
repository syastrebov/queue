<?php

namespace Queue\Service\Queue\Event\Parser;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция парсеров событий слушателя
 *
 * Class ParserCollection
 * @package Queue\Service\Queue\Event\Parser
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить парсер
     *
     * @param ParserPluginInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(ParserPluginInterface $plugin)
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
     * @return ParserPluginInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var ParserPluginInterface $plugin */
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
