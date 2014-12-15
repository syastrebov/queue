<?php

namespace Queue\Service\Queue\Consumer;

/**
 * Менеджер очередей / Исключение консьюмера очередей (срабатывает в обработчике, если неудалось получить сообщение)
 *
 * Class Exception
 * @package Queue\Service\Queue\Consumer
 */
class EmptyException extends \Exception
{

}
