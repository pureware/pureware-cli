<?php
namespace Pureware\PurewareCli\Maker;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Symfony\Component\Console\Input\InputInterface;

interface MakerInterface
{
    /**
     * @param NamespaceResolverInterface $namespaceResolver
     * @param InputInterface $input
     * @param array<string> $options
     * @return DirectoryCollection
     */
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection;
}
