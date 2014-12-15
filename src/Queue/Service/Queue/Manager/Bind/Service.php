<?php

namespace Queue\Service\Queue\Manager\Bind;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface;

/**
 * Менеджер очередей / Адаптер для связи таблицы с обменником
 *
 * Class Service
 * @package Queue\Service\Queue\Manager\Bind
 */
class Service
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Collection
     */
    private $bindCollection;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param Collection       $bindCollection
     */
    public function __construct(AdapterInterface $adapter, Collection $bindCollection)
    {
        $this->adapter        = $adapter;
        $this->bindCollection = $bindCollection;
    }

    /**
     * Инициализация адаптера
     *
     * @return $this
     */
    public function initAdapter()
    {
        $this->adapter->init();
        return $this;
    }

    /**
     * Связать все обменники с очередями
     *
     * @return $this
     */
    public function bindCollection()
    {
        /** @var Configuration $bindConfiguration */
        foreach ($this->bindCollection as $bindConfiguration) {
            // Связываем очередь с обменником для передачи через метод publish
            $this->bindOne($bindConfiguration);

            // Связываем очередь с обменником для autoDelete
            if ($bindConfiguration->getQueueConfiguration()->getTimeoutRoute()) {
                $this->bindOne($bindConfiguration->getQueueConfiguration()->getTimeoutRoute());
            }
        }

        return $this;
    }

    /**
     * Связать очередь с обменником
     *
     * @param Configuration $bindConfiguration
     */
    private function bindOne(Configuration $bindConfiguration)
    {
        $this->adapter->bind(
            $bindConfiguration->getExchangeConfiguration(),
            $bindConfiguration->getQueueConfiguration(),
            $bindConfiguration->getRoutingKey()
        );
    }
}
