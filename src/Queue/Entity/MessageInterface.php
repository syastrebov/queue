<?php

namespace Queue\Entity;

/**
 * Менеджер очередей / Интерфейс сообщения из очереди
 *
 * Interface MessageInterface
 * @package Queue\Entity
 */
interface MessageInterface
{
    const INFINITY_ATTEMPT = -1;

    /**
     * Получить id сообщения
     *
     * @return mixed
     */
    public function getId();

    /**
     * Содержимое сообщения
     *
     * @return mixed
     */
    public function getMessage();

    /**
     * Номер попытки отправки
     *
     * @return int
     */
    public function getAttempt();

    /**
     * Не уменьшать счетчик попыток
     *
     * @return bool
     */
    public function isInfinityAttempt();
}
