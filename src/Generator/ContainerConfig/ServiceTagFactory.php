<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ServiceTagFactory
{

    public function generateServiceTag(string $id, string $tag): ServiceTag {
        return (new ServiceTag())
            ->setServiceId($id)
            ->setTag($tag);
    }

}
