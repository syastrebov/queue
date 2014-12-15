<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Queue;
use Exception;

/**
 * Менеджер очередей / Методы подключения коллекции загруженных очередей
 *
 * Class SetQueueCollectionTrait
 * @package Queue\Service\Queue\Parser
 */
trait SetQueueCollectionTrait
{
    /**
     * @var Queue\ConfigurationCollection
     */
    private $queueCollection;

    /**
     * {@inheritdoc}
     */
    public function setQueueCollection(Queue\ConfigurationCollection $queueCollection)
    {
        $this->queueCollection = $queueCollection;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueCollection()
    {
        if (!$this->queueCollection) {
            throw new Exception('Не задана коллекция очередей');
        }

        return $this->queueCollection;
    }
}
