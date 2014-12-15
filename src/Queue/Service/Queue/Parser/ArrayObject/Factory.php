<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Queue\Service\Queue\Parser;
use Queue\Service\Queue\Manager\Decoder;
use Exception;

/**
 * Менеджер очередей / Фабрика сборки парсера конфигурации из массива
 *
 * Class Factory
 * @package Queue\Service\Queue\Parser\ArrayObject
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

        $collection = new Parser\AdapterCollection();
        $collection
            ->attach($this->getMockAdapter())
            ->attach(new Adapter\Rabbit());

        $parser = new Service($collection, new Queue(new Bind()), new Exchange(), new Bind());
        $parser->setRawConfig($this->getRawConfig());

        return $parser;
    }

    /**
     * Получить конфигурацию
     *
     * @return array
     * @throws Exception
     */
    private function getRawConfig()
    {
        $config = $this->serviceLocator->get('Config');
        if (empty($config['queue'])) {
            throw new Exception('Не найдена конфигурация');
        }

        return $config['queue'];
    }

    /**
     * Парсер для mock адаптера
     *
     * @return Adapter\Mock
     */
    private function getMockAdapter()
    {
        $collection = new Decoder\Collection();
        $collection
            ->attach(new Decoder\Json())
            ->attach(new Decoder\Plain());

        return new Adapter\Mock(new Adapter\MockMessage($collection));
    }
}
