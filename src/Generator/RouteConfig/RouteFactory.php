<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\RouteConfig;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceInterface;

class RouteFactory
{
    public function generateRoute(string $route): ServiceInterface
    {
        return (new Route())->setRoute($route);
    }
}
