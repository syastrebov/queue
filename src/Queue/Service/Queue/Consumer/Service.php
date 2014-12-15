<?php

namespace Queue\Service\Queue\Consumer;

use Queue\Entity\MessageInterface;
use Queue\Service\Queue\Event\EventInterface;
use Queue\Service\Queue\Manager\Decoder\DecoderInterface;
use Queue\Service\Queue\Manager\ManagerInterface;
use Queue\Service\Queue\Event\Parser;
use Queue\Service\Queue\Manager\Queue\QueueInterface;

/**
 * Менеджер очередей / Сервис обработки очереди
 *
 * Class Service
 * @package Queue\Service\Queue\Consumer
 */
class Service
{
    /**
     * @var ManagerInterface
     */
    private $queueManager;

    /**
     * @var Plugin\Collection
     */
    private $pluginCollection;

    /**
     * @var Parser\ParserInterface
     */
    private $parser;

    /**
     * Constructor
     *
     * @param ManagerInterface       $queueManager
     * @param Parser\ParserInterface $parser
     * @param Plugin\Collection      $pluginCollection
     */
    public function __construct(
        ManagerInterface       $queueManager,
        Parser\ParserInterface $parser,
        Plugin\Collection      $pluginCollection
    ) {
        $this->queueManager     = $queueManager;
        $this->pluginCollection = $pluginCollection;
        $this->parser           = $parser;
    }

    /**
     * Добавить плагин
     *
     * @param Plugin\PluginInterface $plugin
     * @return $this
     */
    public function attach(Plugin\PluginInterface $plugin)
    {
        $this->pluginCollection->attach($plugin);
        return $this;
    }

    /**
     * Обработать сообщение
     *
     * @param string $queueName
     *
     * @return Response\Response
     * @throws EmptyException
     */
    public function consumeOne($queueName)
    {
        /** @var MessageInterface $message */
        $queue   = $this->queueManager->init()->getQueue($queueName);
        $message = $queue->get();
        if (!$message) {
            throw new EmptyException();
        }

        $response = new Response\Response();

        try {
            $response->addInfoMessage(sprintf('apply message %s', @json_encode($message->getMessage())));
            $this->applyMessage($queue, $message, $response);
            $response->addInfoMessage('success');

        } catch (Plugin\PluginException $pluginException) {
            $response->addErrorMessage($pluginException->getMessage());
            $this->rejectMessage($queue, $message, $response);
        } catch (Parser\Exception $parserException) {
            $response->addErrorMessage($parserException->getMessage());
            $queue->delete($message->getId());
        }

        return $response;
    }

    /**
     * Получить объект события из сообщения очереди
     *
     * @param MessageInterface $message
     *
     * @return EventInterface
     * @throws Parser\Exception
     */
    private function parseEvent(MessageInterface $message)
    {
        $event = $message->getMessage();
        if (!($event instanceof EventInterface)) {
            $event = $this->parser->toEvent($event);
            if (!($event instanceof EventInterface)) {
                throw new Parser\Exception('Не удалось получить сообщение');
            }
        }

        return $event;
    }

    /**
     * Обработка сообщения
     * После успешной обработки удаляем сообщение из очереди
     *
     * @param QueueInterface    $queue
     * @param MessageInterface  $message
     * @param Response\Response $consumerResponse
     * 
     * @throws Plugin\PluginException
     */
    private function applyMessage(QueueInterface $queue, MessageInterface $message, Response\Response $consumerResponse)
    {
        $acceptCount = 0;
        foreach ($this->pluginCollection as $plugin) {
            /** @var Plugin\PluginInterface $plugin */
            $plugin->setEvent($this->parseEvent($message));

            if ($plugin->shouldStart()) {
                $consumerResponse->addInfoMessage(sprintf('plugin started %s', get_class($plugin)));

                $pluginResponse = $plugin->apply();
                if ($pluginResponse instanceof Response\Response) {
                    $consumerResponse->merge($consumerResponse);
                }

                $acceptCount++;
            }
        }
        if (!$acceptCount) {
            throw new Plugin\PluginException('Не найдено ни одного обработчика');
        }

        // Удаляем после обработки
        $queue->delete($message->getId());
    }

    /**
     * Обработка ошибки обработки сообщения из очереди
     *
     * @param QueueInterface    $queue
     * @param MessageInterface  $message
     * @param Response\Response $consumerResponse
     */
    private function rejectMessage(QueueInterface $queue, MessageInterface $message, Response\Response $consumerResponse)
    {
        if ($queue->getConfiguration()->getRejectRoute()) {
            // Очередь может пересылать письмо в случае ошибки
            // Есть еще попытки, пересылаем в связанный обменник
            if ($message->getAttempt() > 0 || $message->isInfinityAttempt()) {
                $rejectRoute = $queue->getConfiguration()->getRejectRoute();
                $newAttempt  = $message->isInfinityAttempt() ? $message->getAttempt() : $message->getAttempt() - 1;

                $this->queueManager
                    ->getExchange($rejectRoute->getExchangeConfiguration()->getName())
                    ->publish(
                        $rejectRoute->getRoutingKey(),
                        $message->getMessage(),
                        $this->getDecoderContentType($message),
                        $newAttempt
                    );

                $consumerResponse->addInfoMessage(sprintf('requeue (attempts left = %d)', $newAttempt));

            } else {
                // Кончились попытки отправки, ничего не делаем
                // Вероятно нужен еще один обменник для таких сообщений

                $consumerResponse->addErrorMessage('no attemps left, delete');
            }

            // Удаляем после обработки
            $queue->delete($message->getId());

        } else {
            // Если пересылать некуда, то возвращаем его в очередь
            // Если сообщение никогда не обработается, с одним консьюмером очередь так и будет висеть на этом сообщении
            // и не перейдет на следующее сообщение, так как нет обменника для пересылки битого сообщения

            $queue->unlock($message->getId());
            $consumerResponse->addWarningMessage('infinity requeue');
        }
    }

    /**
     * Определить тип кодировании при пересылке
     *
     * @param MessageInterface $message
     * @return string
     */
    private function getDecoderContentType(MessageInterface $message)
    {
        if ($message->getMessage() instanceof EventInterface) {
            return DecoderInterface::TYPE_SERIALIZE;
        }
        if (is_array($message->getMessage()) || is_object($message->getMessage())) {
            return DecoderInterface::TYPE_JSON;
        }

        return DecoderInterface::TYPE_PLAIN;
    }
}
