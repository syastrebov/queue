<?php

namespace Queue\Service\Queue\Consumer\Response;

/**
 * Менеджер очередей / Отладочное сообщение консьюмера
 *
 * Class Message
 * @package Queue\Service\Queue\Consumer\Response
 */
class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * Constructor
     *
     * @param string $type
     * @param string $message
     */
    public function __construct($type, $message)
    {
        $this->type    = (string)$type;
        $this->message = (string)$message;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }
}
