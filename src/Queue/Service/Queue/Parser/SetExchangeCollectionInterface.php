<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Exchange;
use Exception;

/**
 * Менеджер очередей / Интерфейс подключения коллекции загруженных обменников
 *
 * Interface SetExchangeCollectionInterface
 * @package Queue\Service\Queue\Parser
 */
interface SetExchangeCollectionInterface
{
    /**
     * Задать коллекцию конфигурации обменников
     *
     * @param Exchange\ConfigurationCollection $exchangeCollection
     * @return $this
     */
    public function setExchangeCollection(Exchange\ConfigurationCollection $exchangeCollection);

    /**
     * Коллекция обменников
     *
     * @return Exchange\ConfigurationCollection
     * @throws Exception
     */
    public function getExchangeCollection();
}
