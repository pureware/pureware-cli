<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class {{extensionName|u.camel.title}}Extension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
        // new fields here
        );
    }

    public function getDefinitionClass(): string
    {
        return ''; //@todo add definition class you want to extend
    }
}
