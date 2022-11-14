<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Cms;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagFactory;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

class CmsElementResolverMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {
        $subDirectory = $this->getSubDirectory($input, $options, 'DataResolver');
        $elementName = $input->getArgument('name') ?? $options['elementName'];

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'elementName' => $elementName
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Resolver'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        ServiceTagGenerator::instance()->addService(
            (new ServiceTagFactory())->generateServiceTag($namespaceResolver->getFullNamespace($subDirectory . '/' . $elementName . 'CmsElementResolver'), 'shopware.cms.data_resolver')
        );

        return (new DirectoryCollection([$directory]));
    }

}
