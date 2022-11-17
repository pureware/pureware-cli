<?php

namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class EntityDependentMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        throw new \RuntimeException('make() method not implemented.');
    }

    protected function getSkipPaths()
    {
        return [
            '{{entityName|u.camel.title}}Definition.php',
            '{{entityName|u.camel.title}}Entity.php',
            '{{entityName|u.camel.title}}Collection.php',
        ];
    }
}
