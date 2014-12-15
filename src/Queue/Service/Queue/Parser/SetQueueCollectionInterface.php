<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Queue;
use Exception;

/**
 * Менеджер очередей / Интерфейс подключения коллекции загруженных очередей
 *
 * Interface SetQueueCollectionInterface
 * @package Queue\Service\Queue\Parser
 */
interface SetQueueCollectionInterface
{
    /**
     * Задать коллекцию конфигурации очередей
     *
     * @param Queue\ConfigurationCollection $queueCollection
     * @return $this
     */
    public function setQueueCollection(Queue\ConfigurationCollection $queueCollection);

    /**
     * Коллекция очередей
     *
     * @return Queue\ConfigurationCollection
     * @throws Exception
     */
    public function getQueueCollection();
}
