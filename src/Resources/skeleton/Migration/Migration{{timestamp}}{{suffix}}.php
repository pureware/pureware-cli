<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration{{timestamp}}{{suffix}} extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return {{timestamp}};
    }

    public function update(Connection $connection): void
    {
        // implement update
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
