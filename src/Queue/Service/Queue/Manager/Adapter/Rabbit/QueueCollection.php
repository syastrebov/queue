<?php

namespace Queue\Service\Queue\Manager\Adapter\Rabbit;

use BmCommon\Collection\AbstractCollection;
use AMQPQueue;
use Exception;

/**
 * Менеджер очередей / Коллекция экземпляров AMQP очередей
 *
 * Class QueueCollection
 * @package Queue\Service\Queue\Manager\Adapter\Rabbit
 */
class QueueCollection extends AbstractCollection
{
    /**
     * Добавить очередь
     *
     * @param AMQPQueue $queue
     * @return $this
     * @throws Exception
     */
    public function attach(AMQPQueue $queue)
    {
        if (!$this->getByName($queue->getName(), false)) {
            $this->collection[] = $queue;
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
     * @return AMQPQueue|null
     * @throws Exception
     */
    public function getByName($type, $throwException = true)
    {
        foreach ($this->collection as $queue) {
            /** @var AMQPQueue $queue */
            if ($queue->getName() === $type) {
                return $queue;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}
