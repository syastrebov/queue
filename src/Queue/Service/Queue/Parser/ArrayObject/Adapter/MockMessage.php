<?php

namespace Queue\Service\Queue\Parser\ArrayObject\Adapter;

use Queue\Entity\Message;
use Queue\Service\Queue\Manager\Decoder\Collection;
use Queue\Service\Queue\Manager\Message\EncodedMessage;
use Queue\Service\Queue\Parser\ArrayObject\ArrayParamsInterface;
use Queue\Service\Queue\Parser\ArrayObject\ArrayParamsTrait;

/**
 * Менеджер очередей / Парсер сообщения mock адаптера
 *
 * Class MockMessage
 * @package Queue\Service\Queue\Parser\ArrayObject\Adapter
 */
final class MockMessage implements ArrayParamsInterface
{
    use ArrayParamsTrait;

    /**
     * @var Collection
     */
    private $decoderCollection;

    /**
     * Constructor
     *
     * @param Collection $decoderCollection
     */
    public function __construct(Collection $decoderCollection)
    {
        $this->decoderCollection = $decoderCollection;
    }

    /**
     * Получить экземпляр закодированного сообщения
     *
     * @return EncodedMessage
     */
    public function getEncodedMessage()
    {
        $contentType = $this->getRawParam('contentType');
        return new EncodedMessage(
            new Message(
                $this->getRawParam('id'),
                $this->decoderCollection->getByContentType($contentType)->encode($this->getRawParam('message')),
                0
            ),
            $contentType
        );
    }
}
