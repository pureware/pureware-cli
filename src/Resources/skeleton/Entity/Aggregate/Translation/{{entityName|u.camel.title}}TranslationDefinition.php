{% set tableName = entityPrefix ~ entityName %}
<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use {{parentClassNamespace}}Definition;

class {{entityName|u.camel.title}}TranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = '{{tableName|u.snake}}_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getParentDefinitionClass(): string
    {
        return {{entityName|u.camel.title}}Definition::class;
    }

    public function getEntityClass(): string
    {
        return {{entityName|u.camel.title}}TranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return {{entityName|u.camel.title}}TranslationCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

        ]);
    }
}
