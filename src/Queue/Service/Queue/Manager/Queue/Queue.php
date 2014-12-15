<?php

namespace Queue\Service\Queue\Manager\Queue;

use Queue\Entity\MessageInterface;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Exception;

/**
 * Менеджер очередей / Очередь
 *
 * Class Queue
 * @package Queue\Service\Queue\Manager\Queue
 */
final class Queue implements QueueInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param Configuration    $configuration
     */
    public function __construct(AdapterInterface $adapter, Configuration $configuration)
    {
        $this->adapter       = $adapter;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->configuration->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return clone $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->adapter->get($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function shift()
    {
        $message = $this->get();
        if ($message instanceof MessageInterface) {
            $this->delete($message->getId());
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $this->adapter->ack($this->configuration, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function unlock($id)
    {
        $this->adapter->nack($this->configuration, $id);
    }
}