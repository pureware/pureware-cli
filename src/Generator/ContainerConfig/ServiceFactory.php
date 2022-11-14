<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ServiceFactory
{

    public function generateServiceTag(string $namespace, string $tag): ServiceInterface {
        return (new ServiceTag())
            ->setServiceId($namespace)
            ->setTag($tag);
    }

    public function generateServiceController(string $namespace): ServiceInterface {
        return (new ServiceController())
            ->setServiceId($namespace);
    }

}