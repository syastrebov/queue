<?php

namespace Queue\Service\Queue\Manager\Decoder;

/**
 * Менеджер очередей / Декодер данных очереди в формате JSON
 *
 * Class Json
 * @package Queue\Service\Queue\Manager\Decoder
 */
final class Json implements DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return DecoderInterface::TYPE_JSON;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data)
    {
        $jsonData = @json_encode($data);
        $this->validate($jsonData, $data);

        return $jsonData;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
        $rawData = @json_decode($data, true);
        $this->validate($data, $rawData);

        return $rawData;
    }

    /**
     * Проверка данных
     *
     * @param string $jsonData
     * @param string $rawData
     *
     * @throws Exception
     */
    private function validate($jsonData, $rawData)
    {
        if (!is_string($jsonData) || (!is_array($rawData) && !is_object($rawData))) {
            throw new Exception(sprintf('Переданы неправильные данные %s', $jsonData));
        }
    }
}
