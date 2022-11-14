<?php

namespace Pureware\PurewareCli\Generator\RouteConfig;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceInterface;

class Route implements ServiceInterface
{
    protected string $route = '';

    public function getTemplate(): string {
        $template = '<import resource="%s" type="annotation" />';

        return sprintf($template, $this->route);
    }

    public function getIdentifier(): string {
        return $this->getTemplate();
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }
}
