<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ServiceEntityTag implements ServiceInterface
{
    protected string $xmlNode = 'service';

    protected string $serviceId = '';

    protected string $tag = '';

    protected string $entityName = '';

    public function getTemplate(): string
    {
        $template = '
        <%s id="%s">
            <tag name="%s" entity="%s" />
        </%s>';

        return sprintf($template, $this->xmlNode, $this->serviceId, $this->tag, $this->entityName, $this->xmlNode);
    }

    public function getIdentifier(): string
    {
        return sprintf('<%s id="%s">', $this->xmlNode, $this->serviceId);
    }

    public function setXmlNode(string $xmlNode): self
    {
        $this->xmlNode = $xmlNode;
        return $this;
    }

    public function setServiceId(string $serviceId): self
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityId): self
    {
        $this->entityName = $entityId;
        return $this;
    }
}
