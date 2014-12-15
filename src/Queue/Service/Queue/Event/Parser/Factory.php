<?php

namespace Queue\Service\Queue\Event\Parser;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Менеджер очередей / Фабрика сборки сервиса парсера события
 *
 * Class Factory
 * @package Queue\Service\Queue\Event\Parser
 */
class Factory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return new Service(new Collection());
    }
}
