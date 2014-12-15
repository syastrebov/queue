<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Exception;

/**
 * Менеджер очередей / Методы задания и получения параметров из массива
 *
 * Class ArrayParamsTrait
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
trait ArrayParamsTrait
{
    /**
     * @var array
     */
    private $rawConfig;

    /**
     * {@inheritdoc}
     */
    public function setRawConfig(array $rawConfig)
    {
        $this->rawConfig = $rawConfig;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRawParam($param)
    {
        return isset($this->rawConfig[$param]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRawParam($param)
    {
        if (!$this->hasRawParam($param)) {
            throw new Exception(sprintf('Не найден параметр конфигурации `%s`', $param));
        }

        return $this->rawConfig[$param];
    }
}
