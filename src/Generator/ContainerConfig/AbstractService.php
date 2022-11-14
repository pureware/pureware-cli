<?php

namespace Pureware\PurewareCli\Generator\ContainerConfig;

abstract class AbstractService implements ServiceInterface
{
    protected string $xmlNode = 'service';
    protected string $serviceId = '';
    protected string $tag = '';

    public function setXmlNode(string $xmlNode): ServiceInterface
    {
        $this->xmlNode = $xmlNode;
        return $this;
    }

    public function setServiceId(string $serviceId): ServiceInterface
    {
        $this->serviceId = $serviceId;
        return $this;
    }
}
