<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Cms;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

class CmsBlockMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = 'Resources/app/administration/src';
        $cmsBlockName = $options['cmsBlockName'] ?? $input->getArgument('name');

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'blockName' => $cmsBlockName,
                'moduleName' => 'sw-cms',
                'blockCategory' => $input->getOption('category'),
                'mainJsContent' => $this->getMainJsContent($namespaceResolver),
            ]
        );

        $builder = new TreeBuilder();
        $builder->skip(['module/{{moduleName}}/elements']);

        $directory = $builder->buildTree($this->getTemplatePath('Cms/Element'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $snippetDirectory = 'Resources/app/administration/src/module/';
        $moduleName = 'sw-cms';

        // like: sw-cms.elements.camelCaseCmsElementName.label => YouCustomLabel
        $baseSnippet = [
            'sw-cms' => [
                'blocks' => [
                    ( new UnicodeString($options['cmsBlockName'] ?? $input->getArgument('name')))->camel()->toString() => [
                        'label' => 'Your Custom Label',
                    ],
                ],
            ],
        ];

        $snippetCollection = $this->makeSnippetFiles($namespaceResolver, $input, [
            'subDirectory' => $snippetDirectory,
            'moduleName' => $moduleName,
            'baseSnippet' => json_encode($baseSnippet, JSON_PRETTY_PRINT) ?: '',
        ]);
        $storefrontCollection = $this->makeStorefrontElementFile($namespaceResolver, $input, [
            'blockName' => $cmsBlockName,
        ]);

        return (new DirectoryCollection([$directory]))->merge($snippetCollection)->merge($storefrontCollection);
    }

    protected function getMainJsContent(NamespaceResolverInterface $namespaceResolver): string
    {
        $content = '';

        if (file_exists($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'))) {
            $content = file_get_contents($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'));
        }

        return is_string($content) ? $content : '';
    }

    /**
     * @param array<string> $options
     */
    protected function makeStorefrontElementFile(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        if ($options['blockName'] === '' || $options['blockName'] === '0') {
            throw new \RuntimeException('You need to pass a blockName to create storefront file');
        }

        $collection = new DirectoryCollection();
        $subdirectory = 'Resources/views/storefront/block/';
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subdirectory);
        $generator->getParser()->setTemplateData(
            [
                'blockName' => $options['blockName'],
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Storefront/Block'), $namespaceResolver->getFullNamespace($subdirectory), $subdirectory);
        $collection->add($directory);
        $generator->generate($directory);

        return $collection;
    }
}
