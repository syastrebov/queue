<?php

namespace Queue\Service\Queue\Manager;

use Queue\Service\Queue\Manager\Adapter;
use Queue\Service\Queue\Parser;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Exception;

/**
 * Менеджер очередей / Фабрика создания менеджера очередей
 *
 * Class AdapterFactory
 * @package Queue\Service\Queue\Manager
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
        $adapter = $this->getDecoderAdapter();

        return new Service(
            $this->getQueueCollection($adapter),
            $this->getExchangeCollection($adapter),
            new Bind\Service($adapter, $this->getConfig()->getBindCollection())
        );
    }

    /**
     * Конфигурация менеджера очередей
     *
     * @return Configuration\ConfigurationInterface
     * @throws Exception
     */
    private function getConfig()
    {
        /** @var Parser\ParserInterface $parser */
        $parser = $this->serviceLocator->get('QueueManagerParser');
        if (!($parser instanceof Parser\ParserInterface)) {
            throw new Exception('Не найден парсер конфигурации менеджера');
        }

        return $parser->getConfiguration();
    }

    /**
     * Получить экземпляр адаптера
     *
     * @return Adapter\AdapterInterface
     * @throws Exception
     */
    private function getDecoderAdapter()
    {
        $collection = new Decoder\Collection();
        $collection
            ->attach(new Decoder\Json())
            ->attach(new Decoder\Plain())
            ->attach(new Decoder\Serialize());

        return new Decoder\Adapter($this->getAdapter(), $collection);
    }

    /**
     * Получить экземпляр адаптера
     *
     * @return Adapter\AdapterInterface
     * @throws Exception
     */
    private function getAdapter()
    {
        $adapterConfig = $this->getConfig()->getAdapterConfig();
        switch ($adapterConfig->getType()) {
            case Adapter\AdapterInterface::TYPE_RABBIT:
                /** @var Adapter\Rabbit\Configuration $adapterConfig */
                return new Adapter\Rabbit\Adapter($adapterConfig);
            case Adapter\AdapterInterface::TYPE_MOCK:
                /** @var Adapter\Mock\Configuration $adapterConfig */
                return new Adapter\Mock\Adapter($adapterConfig);
        }

        throw new Exception('Не найден адаптер');
    }

    /**
     * Получить коллекцию очередей
     *
     * @param Adapter\AdapterInterface $adapter
     * @return Queue\Collection
     */
    private function getQueueCollection(Adapter\AdapterInterface $adapter)
    {
        $collection = new Queue\Collection();
        foreach ($this->getConfig()->getQueueCollection() as $config) {
            /** @var Queue\Configuration $config */
            $collection->attach(new Queue\Queue($adapter, $config));
        }

        return $collection;
    }

    /**
     * Получить коллекцию обменников
     *
     * @param Adapter\AdapterInterface $adapter
     * @return Exchange\Collection
     */
    private function getExchangeCollection(Adapter\AdapterInterface $adapter)
    {
        $collection = new Exchange\Collection();
        foreach ($this->getConfig()->getExchangeCollection() as $config) {
            /** @var Exchange\Configuration $config */
            $collection->attach(new Exchange\Exchange($adapter, $config));
        }

        return $collection;
    }
}
