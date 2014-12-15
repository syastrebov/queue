<?php

namespace Queue\Service\Queue\Consumer;

use Queue\Service\Queue\Event\Parser\ParserInterface;
use Queue\Service\Queue\Manager\ManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Менеджер очередей / Фабрика сборки сервиса обработки очереди
 *
 * Class Factory
 * @package Queue\Service\Queue\Consumer
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
        /** @var ParserInterface $parser */
        $parser = $serviceLocator->get('QueueEventParser');

        return new Service($queueManager, $parser, new Plugin\Collection());
    }
}
