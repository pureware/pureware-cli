<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

interface ServiceInterface
{
    public function getTemplate(): string;

    public function getIdentifier(): string;
}
