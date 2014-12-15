<?php

namespace QueueTest\Unit\Parser\ArrayObject;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Parser\ArrayObject;

/**
 * Менеджер очередей / Тестирование парсера из массива
 *
 * Class ServiceTest
 * @package QueueTest\Unit\Parser\ArrayObject
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    private static $testNum;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ArrayObject\Service
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
        $this->config    = [];
        $this->config[0] = [
            'queue' => [
                'adapter' => 'mock',
                'adapters' => [
                    'mock' => [
                        'messages' => [
                            [
                                'id'          => 1,
                                'message'     => 'my message',
                                'contentType' => 'text/plain',
                            ],
                            [
                                'id'          => 2,
                                'message'     => ['message' => 'my message'],
                                'contentType' => 'application/json',
                            ]
                        ],
                    ],
                ],
            ],
        ];
        $this->config[1] = [
            'queue' => [
                'adapter' => 'rabbit',
                'adapters' => [
                    'rabbit' => [
                        'host'     => 'localhost',
                        'port'     => '5672',
                        'vhost'    => '/',
                        'login'    => 'guest',
                        'password' => 'guest',
                    ],
                ],
            ],
        ];
        $this->config[2] = [
            'queue' => [
                'queues' => [
                    [
                        'name' => 'queue',
                        'ttl'  => 0,
                    ],
                    [
                        'name'     => 'delayQueue60',
                        'ttl'      => 60,
                        'ttlRoute' => [
                            'exchange'   => 'exchange',
                            'routingKey' => 'instant',
                        ],
                    ]
                ],
                'exchanges' => [
                    [
                        'name' => 'exchange',
                        'type' => 'direct',
                    ],
                ],
            ],
        ];
        $this->config[3] = [
            'queue' => [
                'exchanges' => [
                    [
                        'name' => 'exchange',
                        'type' => 'direct',
                    ],
                    [
                        'name' => 'exchangeFanout',
                        'type' => 'fanout',
                    ],
                ],
            ],
        ];
        $this->config[4] = [
            'queue' => [
                'queues' => [
                    [
                        'name' => 'queue',
                        'ttl'  => 0,
                    ],
                    [
                        'name'     => 'delayQueue60',
                        'ttl'      => 60,
                        'ttlRoute' => [
                            'exchange'   => 'exchange',
                            'routingKey' => 'instant',
                        ],
                    ]
                ],
                'exchanges' => [
                    [
                        'name' => 'exchange',
                        'type' => 'direct',
                    ],
                    [
                        'name' => 'exchangeFanout',
                        'type' => 'fanout',
                    ],
                ],
                'binds' => [
                    [
                        'queue'      => 'queue',
                        'exchange'   => 'exchange',
                        'routingKey' => 'instant',
                    ],
                    [
                        'queue'      => 'delayQueue60',
                        'exchange'   => 'exchange',
                        'routingKey' => 'delay',
                    ],
                    [
                        'queue'      => 'queue',
                        'exchange'   => 'exchangeFanout',
                        'routingKey' => 'all',
                    ],
                    [
                        'queue'      => 'delayQueue60',
                        'exchange'   => 'exchangeFanout',
                        'routingKey' => 'all',
                    ],
                ],
            ],
        ];
        $this->config[5] = [
            'queue' => [
                'adapter' => 'rabbit',
                'adapters' => [
                    'rabbit' => [
                        'host'     => 'localhost',
                        'port'     => '5672',
                        'vhost'    => '/',
                        'login'    => 'guest',
                        'password' => 'guest',
                    ],
                ],
                'queues' => [
                    [
                        'name' => 'queue',
                        'ttl'  => 0,
                    ],
                    [
                        'name'     => 'delayQueue60',
                        'ttl'      => 60,
                        'ttlRoute' => [
                            'exchange'   => 'exchange',
                            'routingKey' => 'instant',
                        ],
                    ]
                ],
                'exchanges' => [
                    [
                        'name' => 'exchange',
                        'type' => 'direct',
                    ],
                    [
                        'name' => 'exchangeFanout',
                        'type' => 'fanout',
                    ],
                ],
                'binds' => [
                    [
                        'queue'      => 'queue',
                        'exchange'   => 'exchange',
                        'routingKey' => 'instant',
                    ],
                    [
                        'queue'      => 'delayQueue60',
                        'exchange'   => 'exchange',
                        'routingKey' => 'delay',
                    ],
                    [
                        'queue'      => 'queue',
                        'exchange'   => 'exchangeFanout',
                        'routingKey' => 'all',
                    ],
                    [
                        'queue'      => 'delayQueue60',
                        'exchange'   => 'exchangeFanout',
                        'routingKey' => 'all',
                    ],
                ],
            ],
        ];
    }

    /**
     * Создаем экземпляр парсера
     */
    public function setUp()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['set', 'get', 'has']);
        $serviceLocator
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('Config'))
            ->will($this->returnValue($this->config[self::$testNum]));

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */

        $factory = new ArrayObject\Factory();
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
     * Получение конфигурации для Mock адаптера
     */
    public function testMockAdapter()
    {
        $rawConfig = $this->config[self::$testNum]['queue']['adapters']['mock'];
        $config    = $this->service->getAdapterConfig();

        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Adapter\Mock\Configuration', $config);
        $this->assertEquals(AdapterInterface::TYPE_MOCK, $config->getType());

        /** @var \Queue\Service\Queue\Manager\Adapter\Mock\Configuration $config */
        $this->assertEquals(2, $config->getEncodedMessageCollection()->count());

        /** @var \Queue\Service\Queue\Manager\Message\EncodedMessage $message */
        $message = $config->getEncodedMessageCollection()->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Message\EncodedMessage', $message);
        $this->assertEquals($rawConfig['messages'][0]['id'], $message->getId());
        $this->assertEquals($rawConfig['messages'][0]['message'], $message->getMessage());
        $this->assertEquals($rawConfig['messages'][0]['contentType'], $message->getContentType());

        /** @var \Queue\Service\Queue\Manager\Message\EncodedMessage $message */
        $message = $config->getEncodedMessageCollection()->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Message\EncodedMessage', $message);
        $this->assertEquals($rawConfig['messages'][1]['id'], $message->getId());
        $this->assertEquals(json_encode($rawConfig['messages'][1]['message']), $message->getMessage());
        $this->assertEquals($rawConfig['messages'][1]['contentType'], $message->getContentType());
    }

    /**
     * Получение конфигурации для Rabbit адаптера
     */
    public function testRabbitAdapter()
    {
        $rawConfig = $this->config[self::$testNum]['queue']['adapters']['rabbit'];
        $config    = $this->service->getAdapterConfig();

        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Adapter\Rabbit\Configuration', $config);
        $this->assertEquals(AdapterInterface::TYPE_RABBIT, $config->getType());

        /** @var \Queue\Service\Queue\Manager\Adapter\Rabbit\Configuration $config */
        $this->assertEquals($rawConfig['host'], $config->getHost());
        $this->assertEquals($rawConfig['port'], $config->getPort());
        $this->assertEquals($rawConfig['vhost'], $config->getVirtualHost());
        $this->assertEquals($rawConfig['login'], $config->getLogin());
        $this->assertEquals($rawConfig['password'], $config->getPassword());
        $this->assertEquals($rawConfig, $config->toAMQPConnectionParams());
    }

    /**
     * Получение коллекции конфигурации очередей
     */
    public function testQueueConfigurationCollection()
    {
        /** @var \Queue\Service\Queue\Manager\Queue\ConfigurationCollection $config */
        $rawConfig = $this->config[self::$testNum]['queue']['queues'];
        $config    = $this->service->getQueueCollection();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Queue\ConfigurationCollection', $config);
        $this->assertEquals(2, $config->count());

        /** @var \Queue\Service\Queue\Manager\Queue\Configuration $queueConfiguration */
        $queueConfiguration = $config->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Queue\Configuration', $queueConfiguration);
        $this->assertEquals($rawConfig[0]['name'], $queueConfiguration->getName());
        $this->assertEquals($rawConfig[0]['ttl'], $queueConfiguration->getTimeout());
        $this->assertEquals($rawConfig[0]['ttl'] * 1000, $queueConfiguration->getMicroTimeout());
        $this->assertNull($queueConfiguration->getTimeoutRoute());

        /** @var \Queue\Service\Queue\Manager\Queue\Configuration $queueConfiguration */
        $queueConfiguration = $config->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Queue\Configuration', $queueConfiguration);
        $this->assertEquals($rawConfig[1]['name'], $queueConfiguration->getName());
        $this->assertEquals($rawConfig[1]['ttl'], $queueConfiguration->getTimeout());
        $this->assertEquals($rawConfig[1]['ttl'] * 1000, $queueConfiguration->getMicroTimeout());
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Bind\Configuration', $queueConfiguration->getTimeoutRoute());
    }

    /**
     * Получение коллекции конфигурации обменников
     */
    public function testExchangeConfigurationCollection()
    {
        /** @var \Queue\Service\Queue\Manager\Exchange\ConfigurationCollection $config */
        $rawConfig = $this->config[self::$testNum]['queue']['exchanges'];
        $config    = $this->service->getExchangeCollection();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Exchange\ConfigurationCollection', $config);
        $this->assertEquals(2, $config->count());

        /** @var \Queue\Service\Queue\Manager\Exchange\Configuration $exchangeConfiguration */
        $exchangeConfiguration = $config->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Exchange\Configuration', $exchangeConfiguration);
        $this->assertEquals($rawConfig[0]['name'], $exchangeConfiguration->getName());
        $this->assertEquals($rawConfig[0]['type'], $exchangeConfiguration->getType());

        /** @var \Queue\Service\Queue\Manager\Exchange\Configuration $exchangeConfiguration */
        $exchangeConfiguration = $config->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Exchange\Configuration', $exchangeConfiguration);
        $this->assertEquals($rawConfig[1]['name'], $exchangeConfiguration->getName());
        $this->assertEquals($rawConfig[1]['type'], $exchangeConfiguration->getType());
    }

    /**
     * Получение коллекции конфигурации связей
     */
    public function testBindConfigurationCollection()
    {
        /** @var \Queue\Service\Queue\Manager\Bind\Collection $config */
        $rawConfig = $this->config[self::$testNum]['queue']['binds'];
        $config    = $this->service->getBindCollection();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Bind\Collection', $config);
        $this->assertEquals(4, $config->count());

        /** @var \Queue\Service\Queue\Manager\Bind\Configuration $bindConfiguration */
        $bindConfiguration = $config->shift();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Bind\Configuration', $bindConfiguration);
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Queue\Configuration', $bindConfiguration->getQueueConfiguration());
        $this->assertEquals($rawConfig[0]['queue'], $bindConfiguration->getQueueConfiguration()->getName());
        $this->assertEquals($rawConfig[0]['exchange'], $bindConfiguration->getExchangeConfiguration()->getName());
        $this->assertEquals($rawConfig[0]['routingKey'], $bindConfiguration->getRoutingKey());
    }

    /**
     * Получение конфигурации
     */
    public function testGetConfiguration()
    {
        $config = $this->service->getConfiguration();
        $this->assertInstanceOf('\Queue\Service\Queue\Manager\Configuration\Configuration', $config);
    }
}
