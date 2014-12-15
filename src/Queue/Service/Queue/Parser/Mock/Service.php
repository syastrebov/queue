<?php

namespace Queue\Service\Queue\Parser\Mock;

use Queue\Service\Queue\Manager\Configuration\ConfigurationInterface;
use Queue\Service\Queue\Parser\ParserInterface;

/**
 * Менеджер очередей / Заглушка парсера
 *
 * Class Service
 * @package Queue\Service\Queue\Parser\Mock
 */
class Service implements ParserInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
