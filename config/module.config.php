<?php

return [
    'service_manager' => [
        'factories' => [
            'QueueManagerParser'     => 'Queue\Service\Queue\Parser\ArrayObject\Factory',
            'QueueEventParser'       => 'Queue\Service\Queue\Event\Parser\Factory',
            'QueueParserListener'    => 'Queue\Service\Queue\Event\Listener\Parser\Factory',
            'QueueSerializeListener' => 'Queue\Service\Queue\Event\Listener\Serialize\Factory',
            'QueueManager'           => 'Queue\Service\Queue\Manager\Factory',
            'QueueConsumer'          => 'Queue\Service\Queue\Consumer\Factory',
        ],
    ],
];