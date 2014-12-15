<?php

namespace Queue\Entity;

/**
 * Менеджер очередей / Сообщение из очереди
 *
 * Class Message
 * @package Queue\Entity
 */
class Message implements MessageInterface
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @var mixed
     */
    private $message;

    /**
     * @var
     */
    private $attempt;

    /**
     * Constructor
     *
     * @param mixed $id
     * @param mixed $message
     * @param int   $attempt
     */
    public function __construct($id, $message, $attempt)
    {
        $this->id      = $id;
        $this->message = $message;
        $this->attempt = (int)$attempt;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * {@inheritdoc}
     */
    public function isInfinityAttempt()
    {
        return $this->getAttempt() === MessageInterface::INFINITY_ATTEMPT;
    }
}