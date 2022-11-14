<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

interface ServiceInterface
{
    public function getTemplate(): string;

    public function getIdentifier(): string;

    public function setXmlNode(string $xmlNode): self;

    public function setServiceId(string $serviceId): self;

    public function setTag(string $tag): self;
}
