<?php declare(strict_types=1);

namespace {{fileNamespace}};

{% if isStorefront %}
use Shopware\Storefront\Controller\StorefrontController;
{% else %}
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
{% endif %}
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\System\SalesChannel\NoContentResponse;

/**
 * @Route(defaults={"_routeScope"={"{{routeScope}}"}})
 */
class {{controllerName}} extends {% if isStorefront %}StorefrontController{% else %}AbstractController {% endif %}

{
    /**
     * @Route("/{{basicRoute}}", name="{{routeName}}", methods={"{{method}}"} {%- if isAjax -%}, defaults={"XmlHttpRequest"=true}{%- endif -%})
     */
    public function {{basicRoute|u.camel}}Method(): Response
    {
        return new NoContentResponse();
    }
}
