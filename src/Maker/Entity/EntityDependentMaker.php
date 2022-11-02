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

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {

        throw new \RuntimeException('make() method not implemented.');

        $subDirectory = $input->getOption('workingDir') ?? $options['workingDir'];
        if (!$subDirectory) {
            throw new \RuntimeException('You need to pass a workingDir');
        }

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);

        $generator->getParser()->setTemplateData(
            [
                'entityName' => $options['entityName'] ?? $input->getOptions('entityName'),
                'entityPrefix' => $options['entityPrefix'] ?? $input->getOptions('entityPrefix')
            ]
        );



        $treeBuilder = new TreeBuilder();
        $treeBuilder->skip($skipPaths);
        $hydratorDirectory = $treeBuilder->buildTree($this->getTemplatePath('Entity'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($hydratorDirectory);

        return new DirectoryCollection([$hydratorDirectory]);
    }

    protected function getSkipPaths() {
        return [
            '{{entityName|u.camel.title}}Definition.php',
            '{{entityName|u.camel.title}}Entity.php',
            '{{entityName|u.camel.title}}Collection.php',
        ];
    }

    protected function getSubDirectory(InputInterface $input, array $options) {
        $subDirectory = $input->getOption('workingDir') ?? $options['workingDir'];
        if (!$subDirectory) {
            throw new \RuntimeException('You need to pass a workingDir');
        }

        return $subDirectory;
    }

}
