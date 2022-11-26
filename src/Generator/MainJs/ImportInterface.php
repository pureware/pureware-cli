<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\MainJs;

interface ImportInterface
{
    public function getTemplate(): string;

    public function getIdentifier(): string;
}
