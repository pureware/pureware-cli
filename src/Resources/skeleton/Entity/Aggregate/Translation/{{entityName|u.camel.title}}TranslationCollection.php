{% set entityClass = entityName|u.camel.title ~ 'TranslationEntity' %}
<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;


/**
 * @method void               add({{entityClass}} $entity)
 * @method void               set(string $key, {{entityClass}} $entity)
 * @method {{entityClass}}[]    getIterator()
 * @method {{entityClass}}[]    getElements()
 * @method {{entityClass}}|null get(string $key)
 * @method {{entityClass}}|null first()
 * @method {{entityClass}}|null last()
 */
class {{entityName|u.camel.title}}TranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return {{entityClass ~ '::class'}};
    }
}
