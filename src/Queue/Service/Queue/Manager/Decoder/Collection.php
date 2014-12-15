<?php

namespace Queue\Service\Queue\Manager\Decoder;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция декодеров сообщений
 *
 * Class Collection
 * @package Queue\Service\Queue\Manager\Decoder
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить декодер
     *
     * @param DecoderInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(DecoderInterface $plugin)
    {
        if (!$this->getByContentType($plugin->getContentType(), false)) {
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
     * @return DecoderInterface|null
     * @throws Exception
     */
    public function getByContentType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var DecoderInterface $plugin */
            if ($plugin->getContentType() === $type) {
                return $plugin;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}
