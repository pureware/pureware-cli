<?php

namespace Pureware\PurewareCli\Generator\ContainerConfig;

class ScheduledTask extends AbstractService implements ServiceInterface
{
    private string $handlerServiceId;

    public function getTemplate(): string
    {
        $template = '
        <service id="%s">
            <tag name="shopware.scheduled.task" />
        </service>

        <service id="%s">
            <argument type="service" id="scheduled_task.repository" />
            <tag name="messenger.message_handler" />
        </service>';

        return sprintf($template, $this->serviceId, $this->handlerServiceId);
    }

    public function getIdentifier(): string
    {
        return sprintf('<%s id="%s"', $this->xmlNode, $this->serviceId);
    }

    public function getHandlerServiceId(): string
    {
        return $this->handlerServiceId;
    }

    public function setHandlerServiceId(string $handlerServiceId): self
    {
        $this->handlerServiceId = $handlerServiceId;
        return $this;
    }
}
