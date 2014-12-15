<?php

namespace Queue\Service\Queue\Parser\ArrayObject;

use Exception;

/**
 * Менеджер очередей / Интерфейс задания и получения параметров из массива
 *
 * Interface ArrayParamsInterface
 * @package Queue\Service\Queue\Parser\ArrayObject
 */
interface ArrayParamsInterface
{
    /**
     * Задать конфигурацию
     *
     * @param array $rawConfig
     * @return $this
     */
    public function setRawConfig(array $rawConfig);

    /**
     * Есть параметр
     *
     * @param string $param
     * @return bool
     */
    public function hasRawParam($param);

    /**
     * Параметр конфигурации
     *
     * @param string $param
     * @return mixed
     * @throws Exception
     */
    public function getRawParam($param);
}
