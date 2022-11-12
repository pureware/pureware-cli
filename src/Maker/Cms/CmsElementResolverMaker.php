<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Cms;

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

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'elementName' => $input->getArgument('name') ?? $options['elementName']
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Resolver'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $this->addServiceTag($namespaceResolver);

        return (new DirectoryCollection([$directory]));
    }

}
