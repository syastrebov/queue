<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Queue\Service\Queue\Manager\Configuration\Configuration;
use Queue\Service\Queue\Manager\Queue;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Bind;
use Queue\Service\Queue\Parser\AbstractParser;
use Queue\Service\Queue\Parser\AdapterCollection;
use Queue\Service\Queue\Parser\ArrayObject\Adapter\AdapterInterface;
use Queue\Service\Queue\Parser\ArrayObject\Exchange as ExchangeParser;
use Queue\Service\Queue\Parser\ArrayObject\Queue as QueueParser;
use Queue\Service\Queue\Parser\ArrayObject\Bind as BindParser;
use Exception;

/**
 * Менеджер очередей / Парсер конфигурации из массива
 *
 * Class Parser
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
final class Service extends AbstractParser implements ArrayParamsInterface
{
    use ArrayParamsTrait;

    /**
     * @var AdapterCollection
     */
    private $adapterParserCollection;

    /**
     * @var QueueParser
     */
    private $queueParser;

    /**
     * @var ExchangeParser
     */
    private $exchangeParser;

    /**
     * @var BindParser
     */
    private $bindParser;

    /**
     * Constructor
     *
     * @param AdapterCollection $adapterParserCollection
     * @param QueueParser       $queueParser
     * @param ExchangeParser    $exchangeParser
     * @param BindParser        $bindParser
     */
    public function __construct(
        AdapterCollection $adapterParserCollection,
        QueueParser       $queueParser,
        ExchangeParser    $exchangeParser,
        BindParser        $bindParser
    ) {
        $this->adapterParserCollection = $adapterParserCollection;
        $this->queueParser             = $queueParser;
        $this->exchangeParser          = $exchangeParser;
        $this->bindParser              = $bindParser;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapterConfig()
    {
        $type = $this->getRawParam('adapter');
        if (!in_array($type, $this->getAdapterTypes(), true)) {
            throw new Exception('Неизвестный тип адаптера');
        }
        if (empty($this->getRawParam('adapters')[$type])) {
            throw new Exception('Не задана конфигурация адаптера');
        }

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapterParserCollection->getByType($type);
        return $adapter->setRawConfig($this->getRawParam('adapters')[$type])->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueCollection()
    {
        $queues = $this->getRawParam('queues');
        if (empty($queues)) {
            throw new Exception('Не найдено ни одной очереди');
        }

        $this->queueParser->setExchangeCollection($this->getExchangeCollection());
        $collection = new Queue\ConfigurationCollection();
        foreach ($queues as $rawQueue) {
            $collection->attach($this->queueParser->setRawConfig($rawQueue)->getConfiguration());
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeCollection()
    {
        $exchanges = $this->getRawParam('exchanges');
        if (empty($exchanges)) {
            throw new Exception('Не найдено ни одного обменника');
        }

        $collection = new Exchange\ConfigurationCollection();
        foreach ($exchanges as $rawExchange) {
            $collection->attach($this->exchangeParser->setRawConfig($rawExchange)->getConfiguration());
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindCollection()
    {
        $binds = $this->getRawParam('binds');
        if (empty($binds)) {
            throw new Exception('Не найдено ни одной связи');
        }

        $this->bindParser->setQueueCollection($this->getQueueCollection());
        $this->bindParser->setExchangeCollection($this->getExchangeCollection());

        $collection = new Bind\Collection();
        foreach ($binds as $rawBind) {
            $collection->attach($this->bindParser->setRawConfig($rawBind)->getConfiguration());
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $configuration = new Configuration();
        $configuration
            ->setAdapterConfig($this->getAdapterConfig())
            ->setQueueCollection($this->getQueueCollection())
            ->setExchangeCollection($this->getExchangeCollection())
            ->setBindCollection($this->getBindCollection());

        return $configuration;
    }
}
