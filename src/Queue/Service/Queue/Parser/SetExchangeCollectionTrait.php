<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Exchange;
use Exception;

/**
 * Менеджер очередей / Методы подключения коллекции загруженных обменников
 *
 * Class SetExchangeCollectionTrait
 * @package Queue\Service\Queue\Parser
 */
trait SetExchangeCollectionTrait
{
    /**
     * @var Exchange\ConfigurationCollection
     */
    private $exchangeCollection;

    /**
     * {@inheritdoc}
     */
    public function setExchangeCollection(Exchange\ConfigurationCollection $exchangeCollection)
    {
        $this->exchangeCollection = $exchangeCollection;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeCollection()
    {
        if (!$this->exchangeCollection) {
            throw new Exception('Не задана коллекция обменников');
        }

        return $this->exchangeCollection;
    }
}
