<?php

namespace Queue\Service\Queue\Parser\ArrayObject\Adapter;

use Queue\Service\Queue\Manager\Adapter\Mock\Configuration;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface as ManagerAdapterInterface;
use Queue\Service\Queue\Manager\Message\EncodedMessageCollection;
use Queue\Service\Queue\Parser\ArrayObject\ArrayParamsTrait;

/**
 * Менеджер очередей / Парсер конфигурации mock адаптера
 *
 * Class Mock
 * @package Queue\Service\Queue\Parser\ArrayObject\Adapter
 */
final class Mock implements AdapterInterface
{
    use ArrayParamsTrait;

    /**
     * @var MockMessage
     */
    private $messageAdapter;

    /**
     * Constructor
     *
     * @param MockMessage $messageAdapter
     */
    public function __construct(MockMessage $messageAdapter)
    {
        $this->messageAdapter = $messageAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return ManagerAdapterInterface::TYPE_MOCK;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $configuration = new Configuration();
        $configuration->setEncodedMessageCollection($this->getEncodedMessageCollection());

        return $configuration;
    }

    /**
     * Получить коллекцию сообщений
     *
     * @return EncodedMessageCollection
     */
    private function getEncodedMessageCollection()
    {
        $messageCollection = new EncodedMessageCollection();
        foreach ($this->getRawParam('messages') as $rawMessage) {
            $messageCollection->attach($this->messageAdapter->setRawConfig($rawMessage)->getEncodedMessage());
        }

        return $messageCollection;
    }
}
