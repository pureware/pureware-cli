{% set tableName = entityPrefix ~ entityName %}
<?php declare(strict_types=1);

namespace {{fileNamespace}};
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;

class {{entityName|u.camel.title}}MappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = '{{tableName|u.snake}}';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
        ]);
    }
}
