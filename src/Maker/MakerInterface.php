<?php
namespace Pureware\PurewareCli\Maker;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Symfony\Component\Console\Input\InputInterface;

interface MakerInterface
{
    /**
     * @param array<string|bool> $options
     */
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection;
}
