<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class {{entityName|u.camel.title}}Entity extends Entity
{
    use EntityIdTrait;
}
