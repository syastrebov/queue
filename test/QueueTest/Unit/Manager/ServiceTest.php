<?php

namespace QueueTest\Unit\Manager;

use Queue\Entity\Message;
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
        ];
    }

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
        $this->service->init();

        $queue    = $this->service->getQueue('');
        $exchange = $this->service->getExchange('');

        // Публикация и выбирание из очереди
        $exchange->publish('', 'message 3', Manager\Decoder\DecoderInterface::TYPE_PLAIN, 0);

        $this->assertInstanceOf('\Queue\Entity\Message', $queue->shift());
        $this->assertInstanceOf('\Queue\Entity\Message', $queue->shift());
        $this->assertInstanceOf('\Queue\Entity\Message', $queue->shift());
        $this->assertNull($this->service->getQueue('')->shift());

        // Тестирование nack
        $exchange->publish('', 'message 4', Manager\Decoder\DecoderInterface::TYPE_PLAIN, 0);

        // Получаем и блокируем сообщение
        $message = $queue->get();
        $this->assertInstanceOf('\Queue\Entity\Message', $message);

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
        $adapterConfig = new Manager\Adapter\Mock\Configuration();
        $adapterConfig->setEncodedMessageCollection(new Manager\Message\EncodedMessageCollection());
        $adapterConfig->getEncodedMessageCollection()
            ->attach(new Manager\Message\EncodedMessage(new Message(1, 'message 1', 0), Manager\Decoder\DecoderInterface::TYPE_PLAIN))
            ->attach(new Manager\Message\EncodedMessage(new Message(2, 'message 2', 0), Manager\Decoder\DecoderInterface::TYPE_PLAIN));

        return $adapterConfig;
    }

    /**
     * Конфигурация для создания сервиса
     *
     * @return Manager\Adapter\Rabbit\Configuration
     */
    private function createServiceConfig()
    {
        $queueCollection = new Manager\Queue\ConfigurationCollection();
        $queueCollection->attach(new Manager\Queue\Configuration(''));

        $exchangeCollection = new Manager\Exchange\ConfigurationCollection();
        $exchangeCollection->attach(new Manager\Exchange\Configuration('', Manager\Exchange\ExchangeInterface::TYPE_DIRECT));

        $config = new Manager\Configuration\Configuration();
        $config
            ->setAdapterConfig($this->getRabbitAdapterConfig())
            ->setQueueCollection($queueCollection)
            ->setExchangeCollection($exchangeCollection)
            ->setBindCollection(new Manager\Bind\Collection());

        return $config;
    }
}
