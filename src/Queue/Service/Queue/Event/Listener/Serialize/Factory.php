<?php

namespace Queue\Service\Queue\Event\Listener\Serialize;

use Queue\Service\Queue\Manager\ManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Менеджер очередей / Фабрика сборки zf2 event manager слушателя входящих событий
 * Отправляет сообщения в очередь в сериализованном виде
 *
 * Class Factory
 * @package Queue\Service\Queue\Event\Listener\Serialize
 */
class Factory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ManagerInterface $queueManager */
        $queueManager = $serviceLocator->get('QueueManager');

        return new Listener($queueManager);
    }
}
