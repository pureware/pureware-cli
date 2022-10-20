{% set tableName = entityPrefix ~ entityName %}
<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
{% if hasTranslation %}
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use {{fileNamespace}}\Aggregate\Translation\{{entityName|u.camel.title}}TranslationDefinition;
{% endif %}


class {{entityName|u.camel.title}}Definition extends EntityDefinition
{
    public const ENTITY_NAME = '{{tableName|u.snake}}';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return {{entityName|u.camel.title}}Entity::class;
    }

    public function getCollectionClass(): string
    {
        return {{entityName|u.camel.title}}Collection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
{% if hasTranslation %}
            (new TranslationsAssociationField(
                {{entityName|u.camel.title}}TranslationDefinition::class,
                '{{tableName|u.snake}}_id'
            ))->addFlags(new ApiAware(), new Required())
{% endif %}
        ]);
    }
}
