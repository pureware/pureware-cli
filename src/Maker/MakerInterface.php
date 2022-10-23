<?php
namespace Pureware\PurewareCli\Maker;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Symfony\Component\Console\Input\InputInterface;

interface MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): void;
}
