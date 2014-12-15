<?php

namespace QueueTest\Integration\Manager;

use Queue\Service\Queue\Manager;
use Queue\Service\Queue\Parser;

/**
 * Менеджер очередей / Тестирование менеджера
 *
 * Class ServiceTest
 * @package QueueTest\Unit\Manager
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    private static $testNum;

    /**
     * @var Manager\Configuration\Configuration
     */
    private $config;

    /**
     * @var Manager\Service
     */
    private $service;

    /**
     * Сбрасываем номер текста
     */
    public static function setUpBeforeClass()
    {
        self::$testNum = 0;
    }

    /**
     * Constructor
     *
     * Задаем конфигурацию для тестов
     */
    public function __construct()
    {
        $this->config = [
            $this->createServiceConfig(),
            $this->fanoutConfig(),
            $this->timeoutRouteConfig(),
            $this->getNackMessageConfig(),
        ];
    }

    /**
     * Настройка менеджера очередей
     */
    public function setUp()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['set', 'get', 'has']);
        $serviceLocator
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('QueueManagerParser'))
            ->will($this->returnValue(new Parser\Mock\Service($this->config[self::$testNum])));

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $factory = new Manager\Factory();
        $this->service = $factory->createService($serviceLocator);
    }

    /**
     * Переходим на следующий тест
     */
    public function tearDown()
    {
        $this->service = null;
        self::$testNum++;
    }

    /**
     * Создание сервиса
     */
    public function testCreateService()
    {
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Service', $this->service);
    }

    /**
     * Отправка сообщения в две очереди
     */
    public function testFanout()
    {
        // Связываем очереди
        $this->service->init();

        $exchange = $this->service->getExchange('testFanoutExchange1');
        $queue1   = $this->service->getQueue('testFanoutQueue1');
        $queue2   = $this->service->getQueue('testFanoutQueue2');

        // Проверяем что в них пусто
        $this->assertNull($queue1->shift());
        $this->assertNull($queue2->shift());

        // Отправляем текстовое сообщение
        $exchange->publish(
            'route',
            'message',
            Manager\Decoder\DecoderInterface::TYPE_PLAIN,
            0
        );

        // Получаем сообщение
        $this->assertInstanceOf('\Queue\Entity\Message', $queue1->shift());
        $this->assertInstanceOf('\Queue\Entity\Message', $queue2->shift());
        $this->assertNull($queue1->shift());
        $this->assertNull($queue2->shift());

        // Отправляем массив
        $exchange->publish(
            'route',
            ['message' =>'my message'],
            Manager\Decoder\DecoderInterface::TYPE_JSON,
            0
        );

        // Получаем сообщение
        $this->assertInstanceOf('\Queue\Entity\Message', $queue1->shift());
        $this->assertInstanceOf('\Queue\Entity\Message', $queue2->shift());
        $this->assertNull($queue1->shift());
        $this->assertNull($queue2->shift());
    }

    /**
     * Пересылка из отложенной очереди в основную
     */
    public function testTimeoutRoute()
    {
        // Связываем очереди
        $this->service->init();

        $queue1 = $this->service->getQueue('testTimeoutRouteQueue1');
        $queue2 = $this->service->getQueue('testTimeoutRouteQueue2');

        // Проверяем что в них пусто
        $this->assertNull($queue1->shift());
        $this->assertNull($queue2->shift());

        // Отправляем в отложенную очередь
        $this->service->getExchange('testTimeoutRouteExchange1')->publish(
            'route',
            ['message' =>'my message'],
            Manager\Decoder\DecoderInterface::TYPE_JSON,
            0
        );

        // Ждем пока упадет в основную
        sleep(2);

        // Получаем сообщение
        $this->assertInstanceOf('\Queue\Entity\Message', $queue2->shift());
        $this->assertNull($queue1->shift());
        $this->assertNull($queue2->shift());
    }

    /**
     * Получение сообщения и отправка его назад в очередь
     */
    public function testGetNackMessage()
    {
        // Связываем очереди
        $this->service->init();

        $exchange = $this->service->getExchange('testGetNackExchange');
        $queue    = $this->service->getQueue('testGetNackQueue');

        // Проверяем что в них пусто
        $this->assertNull($queue->shift());

        // Отправляем в очередь
        $exchange->publish(
            'route',
            ['message' =>'my message'],
            Manager\Decoder\DecoderInterface::TYPE_JSON,
            0
        );

        // Получаем и блокируем сообщение
        $message = $queue->get();
        $this->assertInstanceOf('\Queue\Entity\Message', $message);
        $this->assertNull($queue->shift());

        // Возвращаем в очередь
        $queue->unlock($message->getId());

        // И снова забираем
        $this->assertInstanceOf('\Queue\Entity\Message', $queue->shift());
        $this->assertNull($queue->shift());
    }

    /**
     * Конфигурация rabbit адаптера
     *
     * @return Manager\Adapter\Rabbit\Configuration
     */
    private function getRabbitAdapterConfig()
    {
        $adapterConfig = new Manager\Adapter\Rabbit\Configuration();
        $adapterConfig
            ->setHost('localhost')
            ->setPort('5672')
            ->setVirtualHost('/')
            ->setLogin('guest')
            ->setPassword('guest');

        return $adapterConfig;
    }

    /**
     * Конфигурация для создания сервиса
     *
     * @return Manager\Adapter\Rabbit\Configuration
     */
    private function createServiceConfig()
    {
        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection(new Manager\Queue\ConfigurationCollection())
            ->setExchangeCollection(new Manager\Exchange\ConfigurationCollection())
            ->setBindCollection(new Manager\Bind\Collection());

        return $config;
    }

    /**
     * Конфигурация для отправки сообщения в две очереди
     *
     * @return Manager\Configuration\Configuration
     */
    private function fanoutConfig()
    {
        $queue1 = new Manager\Queue\Configuration('testFanoutQueue1');
        $queue2 = new Manager\Queue\Configuration('testFanoutQueue2');

        $exchange = new Manager\Exchange\Configuration(
            'testFanoutExchange1',
            Manager\Exchange\ExchangeInterface::TYPE_FANOUT
        );

        $queueCollection = new Manager\Queue\ConfigurationCollection();
        $queueCollection->attach($queue1)->attach($queue2);

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange);

        $bindCollection = new Manager\Bind\Collection();
        $bindCollection
            ->attach(new Manager\Bind\Configuration($exchange, $queue1, 'route'))
            ->attach(new Manager\Bind\Configuration($exchange, $queue2, 'route'));

        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection($queueCollection)
            ->setExchangeCollection($exchangeCollection)
            ->setBindCollection($bindCollection);

        return $config;
    }

    /**
     * Конфигурация для пересылки с отложенной очереди
     *
     * @return Manager\Configuration\Configuration
     */
    public function timeoutRouteConfig()
    {
        $exchange1 = new Manager\Exchange\Configuration(
            'testTimeoutRouteExchange1',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );
        $exchange2 = new Manager\Exchange\Configuration(
            'testTimeoutRouteExchange2',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange1)->attach($exchange2);

        $queue1 = new Manager\Queue\Configuration('testTimeoutRouteQueue1', 1);
        $queue1->setTimeoutRoute($exchange2, 'delay');
        $queue2 = new Manager\Queue\Configuration('testTimeoutRouteQueue2');

        $queueCollection = new Manager\Queue\ConfigurationCollection();
        $queueCollection->attach($queue1)->attach($queue2);

        $bindCollection = new Manager\Bind\Collection();
        $bindCollection
            ->attach(new Manager\Bind\Configuration($exchange1, $queue1, 'route'))
            ->attach(new Manager\Bind\Configuration($exchange2, $queue2, 'delay'));

        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection($queueCollection)
            ->setExchangeCollection($exchangeCollection)
            ->setBindCollection($bindCollection);

        return $config;
    }

    /**
     * Получение сообщения и отправка его назад в очередь
     */
    public function getNackMessageConfig()
    {
        $exchange = new Manager\Exchange\Configuration(
            'testGetNackExchange',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange);

        $queue = new Manager\Queue\Configuration('testGetNackQueue');

        $queueCollection = new Manager\Queue\ConfigurationCollection();
        $queueCollection->attach($queue);

        $bindCollection = new Manager\Bind\Collection();
        $bindCollection->attach(new Manager\Bind\Configuration($exchange, $queue, 'route'));

        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection($queueCollection)
            ->setExchangeCollection($exchangeCollection)
            ->setBindCollection($bindCollection);

        return $config;
    }
}
