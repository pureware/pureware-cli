<?php

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ServiceTag implements ServiceInterface
{
    protected string $xmlNode = 'service';
    protected string $serviceId = '';
    protected string $tag = '';

    public function getTemplate(): string {
        $template = '
        <%s id="%s">
            <tag name="%s" />
        </%s>';

        return sprintf($template, $this->xmlNode, $this->serviceId, $this->tag, $this->xmlNode);
    }

    public function getIdentifier(): string {
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
}
