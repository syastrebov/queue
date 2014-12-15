<?php

namespace Queue\Service\Queue\Manager\Message;

use Queue\Entity\Message;
use Queue\Entity\MessageInterface;

/**
 * Менеджер очередей / Закодированное сообщение пришедшее из адаптера
 *
 * Class RawMessage
 * @package Queue\Service\Queue\Manager\Message
 */
class EncodedMessage implements MessageInterface
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var string
     */
    private $contentType;

    /**
     * Constructor
     *
     * @param Message $message
     * @param string  $contentType
     */
    public function __construct(Message $message, $contentType)
    {
        $this->message     = $message;
        $this->contentType = $contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->message->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttempt()
    {
        return $this->message->getAttempt();
    }

    public function isInfinityAttempt()
    {
        return $this->message->isInfinityAttempt();
    }

    /**
     * Получить тип кодирования
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}
