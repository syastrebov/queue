<?php

namespace Queue\Service\Queue\Manager\Queue;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Менеджер очередей / Коллекция очередей
 *
 * Class Collection
 * @package Queue\Service\Queue\Manager\Queue
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить очередь
     *
     * @param QueueInterface $queue
     * @return $this
     * @throws Exception
     */
    public function attach(QueueInterface $queue)
    {
        if (!$this->getByName($queue->getName(), false)) {
            $this->collection[] = $queue;
        } else {
            throw new Exception('Очередь уже была добавлена');
        }

        return $this;
    }

    /**
     * Получить обработчик по типу
     *
     * @param string $type
     * @param bool   $throwException
     *
     * @return QueueInterface|null
     * @throws Exception
     */
    public function getByName($type, $throwException = true)
    {
        foreach ($this->collection as $queue) {
            /** @var QueueInterface $queue */
            if ($queue->getName() === $type) {
                return $queue;
            }
        }
        if ($throwException) {
            throw new Exception('Очередь не найдена');
        }

        return null;
    }
} 