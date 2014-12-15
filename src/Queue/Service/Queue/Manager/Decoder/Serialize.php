<?php

namespace Queue\Service\Queue\Manager\Decoder;

/**
 * Менеджер очередей / Декодер данных очереди в формате phpSerialize
 *
 * Class Serialize
 * @package Queue\Service\Queue\Manager\Decoder
 */
final class Serialize implements DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return DecoderInterface::TYPE_SERIALIZE;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data)
    {
        $serializeData = @serialize($data);
        $this->validate($serializeData, $data);

        return $serializeData;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
        $rawData = @unserialize($data);
        $this->validate($data, $rawData);

        return $rawData;
    }

    /**
     * Проверка данных
     *
     * @param string $serializeData
     * @param string $rawData
     *
     * @throws Exception
     */
    private function validate($serializeData, $rawData)
    {
        if (!is_string($serializeData) || (!is_array($rawData) && !is_object($rawData))) {
            throw new Exception(sprintf('Переданы неправильные данные %s', $serializeData));
        }
    }
}
