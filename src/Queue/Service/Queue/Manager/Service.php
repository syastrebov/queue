<?php

namespace Queue\Service\Queue\Manager;

/**
 * Менеджер очередей / Сервис очередей
 *
 * Class Service
 * @package Queue\Service\Queue\Manager
 */
class Service implements ManagerInterface
{
    /**
     * @var bool
     */
    private $inited = false;

    /**
     * @var Queue\Collection
     */
    private $queueCollection;

    /**
     * @var Exchange\Collection
     */
    private $exchangeCollection;

    /**
     * Constructor
     *
     * @param Queue\Collection      $queueCollection
     * @param Exchange\Collection   $exchangeCollection
     * @param Bind\Service          $bindService
     */
    public function __construct(
        Queue\Collection    $queueCollection,
        Exchange\Collection $exchangeCollection,
        Bind\Service        $bindService
    ) {
        $this->queueCollection    = $queueCollection;
        $this->exchangeCollection = $exchangeCollection;
        $this->bindService        = $bindService;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!$this->inited) {
            $this->bindService->initAdapter()->bindCollection();
            $this->inited = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange($name)
    {
        return $this->exchangeCollection->getByName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue($name)
    {
        return $this->queueCollection->getByName($name);
    }
}
