<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;

class {{elementName|u.camel.title}}CmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return '{{elementName|u.snake|slug}}';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {

        $config = $slot->getFieldConfig();
        $customConfig = $config->get('yourCustomConfigKey');

        if (!$customConfig) {
            return null;
        }

        $criteriaCollection = new CriteriaCollection();
//        $criteria = new Criteria([$mediaId]);
//        $criteriaCollection->add('customKey_' . $slot->getUniqueIdentifier(), MediaDefinition::class, $criteria);

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $config = $slot->getFieldConfig();
        $customConfig = $config->get('yourCustomConfigKey');

        if (!$customConfig) {
            return;
        }
    }
}
