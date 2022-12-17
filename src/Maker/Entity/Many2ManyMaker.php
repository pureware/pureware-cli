<?php

namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class Many2ManyMaker extends EntityDependentMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = $this->getSubDirectory($input, $options);
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'entityName' => $options['entityName'] ?? $input->getArgument('entityName'),
                'entityPrefix' => $options['entityPrefix'] ?? $input->getOption('prefix'),
            ]
        );

        $skipPaths = $this->getSkipPaths();
        $skipPaths[] = 'Aggregate';
        $skipPaths[] = '{{entityName|u.camel.title}}Hydrator.php';

        $treeBuilder = new TreeBuilder();
        $treeBuilder->skip($skipPaths);
        $directory = $treeBuilder->buildTree($this->getTemplatePath('Entity'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        return new DirectoryCollection([$directory]);
    }
}
