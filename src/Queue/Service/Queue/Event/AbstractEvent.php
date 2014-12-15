<?php

namespace Queue\Service\Queue\Event;

use Zend\EventManager\Event;

/**
 * Менеджер очередей / Базовый класс события обрабатываемого через очередь
 *
 * Class AbstractEvent
 * @package Queue\Service\Queue\Event
 */
abstract class AbstractEvent extends Event implements EventInterface
{

}
