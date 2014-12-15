<?php

namespace Queue\Service\Queue\Consumer\Plugin;

use Closure;

/**
 * Менеджер очередей / Тестовый плагин
 *
 * Class Mock
 * @package Queue\Service\Queue\Consumer\Plugin
 */
class Mock extends AbstractPlugin
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Constructor
     *
     * @param Closure $callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return PluginInterface::TYPE_MOCK;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldStart()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var callable $callback */
        $callback = $this->callback;
        $callback($this->event);
    }
}
