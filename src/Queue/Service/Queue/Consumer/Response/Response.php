<?php

namespace Queue\Service\Queue\Consumer\Response;

/**
 * Менеджер очередей / Результат обработки сообщения
 *
 * Class Response
 * @package Queue\Service\Queue\Consumer\Response
 */
class Response
{
    /**
     * @var MessageCollection
     */
    private $messages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new MessageCollection();
    }

    /**
     * Отладочные сообщения
     *
     * @return array
     */
    public function getMessages()
    {
        return clone $this->messages;
    }

    /**
     * Добавить отладочное сообщение
     *
     * @param string $message
     * @return $this
     */
    public function addInfoMessage($message)
    {
        $this->messages->attach(new Message(MessageInterface::TYPE_INFO, $message));
        return $this;
    }

    /**
     * Отладочные сообщения
     *
     * @return array
     */
    public function getInfoMessages()
    {
        return $this->messages->getByType(MessageInterface::TYPE_INFO);
    }

    /**
     * Добавить предупреждающее сообщение
     *
     * @param string $message
     * @return $this
     */
    public function addWarningMessage($message)
    {
        $this->messages->attach(new Message(MessageInterface::TYPE_WARNING, $message));
        return $this;
    }

    /**
     * Предупреждающие ообщения
     *
     * @return array
     */
    public function getWarningMessages()
    {
        return $this->messages->getByType(MessageInterface::TYPE_WARNING);
    }

    /**
     * Добавить сообщение об ошибке
     *
     * @param string $message
     * @return $this
     */
    public function addErrorMessage($message)
    {
        $this->messages->attach(new Message(MessageInterface::TYPE_ERROR, $message));
        return $this;
    }

    /**
     * Сообщения об ошибке
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->messages->getByType(MessageInterface::TYPE_ERROR);
    }

    /**
     * Добавить результат
     *
     * @param Response $response
     * @return $this
     */
    public function merge(Response $response)
    {
        foreach ($response->getMessages() as $message) {
            $this->messages->attach($message);
        }

        return $this;
    }
}
