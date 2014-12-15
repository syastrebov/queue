<?php

namespace Queue\Service\Queue\Manager\Adapter\Rabbit;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Manager\Adapter\ConfigurationInterface;

/**
 * Менеджер очередей / Конфигурация адаптера rabbitMQ
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Adapter\Rabbit
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $virtualHost;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return AdapterInterface::TYPE_RABBIT;
    }

    /**
     * Задать хост
     *
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Хост
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Задать порт
     *
     * @param string $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = (int)$port;
        return $this;
    }

    /**
     * Порт
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Задать виртуальный хост
     *
     * @param string $virtualHost
     * @return $this
     */
    public function setVirtualHost($virtualHost)
    {
        $this->virtualHost = $virtualHost;
        return $this;
    }

    /**
     * Виртуальный хост
     *
     * @return string
     */
    public function getVirtualHost()
    {
        return $this->virtualHost;
    }

    /**
     * Задать логин
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Логин для подключения
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Задать пароль
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Пароль для подключения
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Преобразовать в массив для AMQPConnection
     *
     * @return array
     */
    public function toAMQPConnectionParams()
    {
        return [
            'host'     => $this->getHost(),
            'port'     => $this->getPort(),
            'vhost'    => $this->getVirtualHost(),
            'login'    => $this->getLogin(),
            'password' => $this->getPassword(),
        ];
    }
}
