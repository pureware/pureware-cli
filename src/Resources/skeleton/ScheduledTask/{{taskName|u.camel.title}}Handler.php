<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

class {{taskName|u.camel.title}}Handler extends ScheduledTaskHandler
{
    public static function getHandledMessages(): iterable
    {
        return [ {{taskName|u.camel.title}}::class ];
    }

    public function run(): void
    {
        //handle the task
    }
}
