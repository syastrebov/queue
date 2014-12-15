<?php

namespace Queue\Service\Queue\Manager\Decoder;

/**
 * Менеджер очередей / Декодер входящих данных из очереди
 *
 * Interface DecoderInterface
 * @package Queue\Service\Queue\Manager\Decoder
 */
interface DecoderInterface
{
    const TYPE_PLAIN     = 'text/plain';
    const TYPE_JSON      = 'application/json';
    const TYPE_SERIALIZE = 'application/vnd.php.serialized';

    /**
     * Тип плагина
     *
     * @return string
     */
    public function getContentType();

    /**
     * Преобразовать данные в хранимый формат
     *
     * @throws Exception
     * @param mixed $data
     * @return mixed
     */
    public function encode($data);

    /**
     * Преобразовать данные из хранимого формата
     *
     * @param mixed $data
     * @return mixed
     * @throws Exception
     */
    public function decode($data);
}
