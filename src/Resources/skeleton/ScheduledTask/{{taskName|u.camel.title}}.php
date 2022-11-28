<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class {{taskName|u.camel.title}} extends ScheduledTask
{
    public static function getTaskName(): string
    {
{% if prefix %}
        return '{{prefix|u.snake}}.{{taskName|u.snake}}';
{% else %}
        return '{{taskName|u.snake}}';
{% endif %}
    }

    public static function getDefaultInterval(): int
    {
        return {{defaultInterval}}; // {% if defaultInterval < 3600 %} {{defaultInterval / 60}} minutes {% else %} {{(defaultInterval / 60) / 60 }} hours {% endif %}

    }
}
