<?php

namespace Queue\Service\Queue\Manager\Decoder;

/**
 * Менеджер очередей / Исключение декодера сообщений
 *
 * Class Exception
 * @package Queue\Service\Queue\Manager\Decoder
 */
class Exception extends \Exception
{
    /**
     * Дополнительные данные
     *
     * @var mixed
     */
    private $data;

    /**
     * Constructor
     *
     * @param string $message
     * @param mixed  $data
     */
    public function __construct($message, $data = null)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    /**
     * Дополнительные данные
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Дополнительные данные в виде строки
     *
     * @return string
     */
    public function getDataAsString()
    {
        if (is_array($this->data) || is_object($this->data)) {
            return @serialize($this->data);
        }

        return (string)$this->data;
    }
}
