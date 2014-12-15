<?php

namespace QueueTest\Integration\Consumer;

use Queue\Service\Queue\Consumer;
use Queue\Service\Queue\Manager;
use Queue\Service\Queue\Parser;
use Queue\Service\Queue\Event;

/**
 * Менеджер очередей / Тестирование консьюмера
 *
 * Class ServiceTest
 * @package QueueTest\Integration\Consumer
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
    private $manager;

    /**
     * @var Event\Parser\Service
     */
    private $eventParser;

    /**
     * @var Consumer\Service
     */
    private $consumer;

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
            $this->attemptsConfig(),
            $this->reQueueConfig(),
            $this->pluginConfig(),
        ];
    }

    /**
     * Настройка консьюмера очередей
     */
    public function setUp()
    {
        $config = isset($this->config[self::$testNum]) ? $this->config[self::$testNum] : null;
        if (!($config instanceof Manager\Configuration\Configuration)) {
            throw new \Exception('Неверная конфигурация');
        }

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['set', 'get', 'has']);
        $serviceLocator
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('QueueManagerParser'))
            ->will($this->returnValue(new Parser\Mock\Service($config)));

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $factory       = new Manager\Factory();
        $this->manager = $factory->createService($serviceLocator);

        $this->eventParser = new Event\Parser\Service(new Event\Parser\Collection());

        /** @var \PHPUnit_Framework_MockObject_MockObject $serviceLocator */
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['set', 'get', 'has']);
        $serviceLocator
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('QueueManager'))
            ->will($this->returnValue($this->manager));
        $serviceLocator
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('QueueEventParser'))
            ->will($this->returnValue($this->eventParser));

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $factory = new Consumer\Factory();
        $this->consumer = $factory->createService($serviceLocator);
    }

    /**
     * Переходим на следующий тест
     */
    public function tearDown()
    {
        $this->manager  = null;
        $this->consumer = null;

        self::$testNum++;
    }

    /**
     * Тестирование отправки сообщений с попытками
     */
    public function testAttempts()
    {
        $attempts = 5;
        $queue = $this->manager->init()->getQueue('testAttemptsQueue');

        $this->assertNull($queue->shift());
        $this->manager
            ->getExchange('testAttemptsExchange')
            ->publish('route', 'message', Manager\Decoder\DecoderInterface::TYPE_PLAIN, $attempts);

        $attempts++;
        while (--$attempts >= 0) {
            // Проверяем что сообщение лежит в очереди
            $message = $queue->get();
            $this->assertInstanceOf('\Queue\Entity\Message', $message);
            $this->assertEquals($attempts, $message->getAttempt());

            $queue->unlock($message->getId());

            // Обрабатываем сообщение и проверяем что сообщения больше нет в очереди
            $this->consumer->consumeOne('testAttemptsQueue');
            $this->assertNull($queue->shift());

            sleep(2);
        }

        // Сообщение окончательно ушло из очереди
        $this->assertNull($queue->shift());
    }

    /**
     * Тестирование возвращения сообщения в очередь
     */
    public function testReQueue()
    {
        $publishAttempts = 5;
        $attempts = $publishAttempts;

        $queue = $this->manager->init()->getQueue('testReQueueQueue');
        $this->assertNull($queue->shift());
        $this->manager
            ->getExchange('testReQueueExchange')
            ->publish('route', 'message', Manager\Decoder\DecoderInterface::TYPE_PLAIN, $attempts);

        $attempts++;
        while (--$attempts >= 0) {
            // Проверяем что сообщение лежит в очереди
            $message = $queue->get();
            $this->assertInstanceOf('\Queue\Entity\Message', $message);
            $this->assertEquals($publishAttempts, $message->getAttempt());

            $queue->unlock($message->getId());
            $this->consumer->consumeOne('testReQueueQueue');
        }

        // Сообщение окончательно ушло из очереди
        $this->assertInstanceOf('\Queue\Entity\Message', $queue->shift());
    }

    /**
     * Тестирование вызова плагина
     */
    public function testPlugin()
    {
        $this->eventParser->attach(new Event\Parser\Mock());

        $event = new Event\Mock('tesPluginExchange', 'route', 0);
        $queue = $this->manager->init()->getQueue('testPluginQueue');
        $this->assertNull($queue->shift());
        $this->manager
            ->getExchange('testPluginExchange')
            ->publish('route', $event, Manager\Decoder\DecoderInterface::TYPE_SERIALIZE, 1)
            ->publish('route', $this->eventParser->toArray($event), Manager\Decoder\DecoderInterface::TYPE_JSON, 1);

        $consumeEvent = null;
        $this->consumer->attach(
            new Consumer\Plugin\Mock(function(Event\EventInterface $event) use(&$consumeEvent) {
                $consumeEvent = $event;
            })
        );

        $this->consumer->consumeOne('testPluginQueue');
        $this->assertEquals($event, $consumeEvent);

        $consumeEvent = null;
        $this->consumer->consumeOne('testPluginQueue');
        $this->assertEquals($event, $consumeEvent);
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
     * Конфигурация отправки сообщений с попытками
     */
    private function attemptsConfig()
    {
        $exchange = new Manager\Exchange\Configuration(
            'testAttemptsExchange',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );

        // Обменник
        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange);

        // Основная очередь
        $queue = new Manager\Queue\Configuration('testAttemptsQueue');
        $queue->setRejectRoute($exchange, 'reject');

        // Очередь с ошибками
        $rejectQueue = new Manager\Queue\Configuration('testAttemptsRejectQueue', 1);
        $rejectQueue->setTimeoutRoute($exchange, 'rejectRoute');

        $queueCollection = new Manager\Queue\ConfigurationCollection();
        $queueCollection->attach($queue)->attach($rejectQueue);

        // Связываем
        $bindCollection = new Manager\Bind\Collection();
        $bindCollection
            ->attach(new Manager\Bind\Configuration($exchange, $queue, 'route'))
            ->attach(new Manager\Bind\Configuration($exchange, $queue, 'rejectRoute'))
            ->attach(new Manager\Bind\Configuration($exchange, $rejectQueue, 'reject'));

        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection($queueCollection)
            ->setExchangeCollection($exchangeCollection)
            ->setBindCollection($bindCollection);

        return $config;
    }

    /**
     * Конфигурация возвращения сообщения в очередь
     */
    private function reQueueConfig()
    {
        $exchange = new Manager\Exchange\Configuration(
            'testReQueueExchange',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );

        $queue = new Manager\Queue\Configuration('testReQueueQueue');

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange);

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

    /**
     * Конфигурация возвращения сообщения в очередь
     */
    private function pluginConfig()
    {
        $exchange = new Manager\Exchange\Configuration(
            'testPluginExchange',
            Manager\Exchange\ExchangeInterface::TYPE_DIRECT
        );

        $queue = new Manager\Queue\Configuration('testPluginQueue');

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach($exchange);

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
