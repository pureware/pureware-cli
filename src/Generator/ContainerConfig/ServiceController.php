<?php

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ServiceController extends AbstractService implements ServiceInterface
{
    public function getTemplate(): string
    {
        $template = '
        <%s id="%s" public="true">
            <call method="setContainer">
                <argument type="service" id="service_container"/>
            </call>
        </%s>';

        return sprintf($template, $this->xmlNode, $this->serviceId, $this->xmlNode);
    }

    public function getIdentifier(): string
    {
        return sprintf('<%s id="%s"', $this->xmlNode, $this->serviceId);
    }
}
