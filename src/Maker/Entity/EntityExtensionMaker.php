<?php

namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceFactory;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

class EntityExtensionMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $extensionName = $options['extensionName'] ?? $input->getArgument('name');
        $extensionName .= str_replace('Extension', '', $extensionName) . 'Extension';
        $extensionName = (new UnicodeString($extensionName))->camel()->title()->toString();

        $subDirectory = $this->getSubDirectory($input, $options, 'Content/Extension');
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'extensionName' => $extensionName,
            ]
        );

        $treeBuilder = new TreeBuilder();
        $directory = $treeBuilder->buildTree($this->getTemplatePath('EntityExtension'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        ServiceTagGenerator::instance()->addService(
            (new ServiceFactory())->generateServiceTag(
                $namespaceResolver->getFullNamespace($subDirectory . '/' . $extensionName),
                'shopware.entity.extension'
            )
        );

        return new DirectoryCollection([$directory]);
    }
}
