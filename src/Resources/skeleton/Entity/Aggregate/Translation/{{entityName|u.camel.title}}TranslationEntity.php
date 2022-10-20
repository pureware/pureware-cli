<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use {{parentClassNamespace}}Entity;

class {{entityName|u.camel.title}}TranslationEntity extends Entity
{
    protected {{entityName|u.camel.title}}Entity ${{entityName|u.camel}};

    public function get{{entityName|u.camel.title}}Entity(): {{entityName|u.camel.title}}Entity
    {
        return $this->{{entityName|u.camel}};
    }

    public function set{{entityName|u.camel.title}}Entity({{entityName|u.camel.title}}Entity ${{entityName|u.camel}}): void
    {
        $this->{{entityName|u.camel}} = ${{entityName|u.camel}};
    }
}
