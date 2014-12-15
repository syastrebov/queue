<?php

namespace Queue\Service\Queue\Event\Listener\Parser;

use Queue\Service\Queue\Event\Parser\ParserInterface;
use Queue\Service\Queue\Manager\ManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Менеджер очередей / Фабрика сборки zf2 event manager слушателя входящих событий
 * Отправляет сообщения в очередь в json формате
 *
 * Class Factory
 * @package Queue\Service\Queue\Event\Listener\Parser
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

        return new Listener($queueManager, $parser);
    }
}
