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

class CmsElementMaker extends AbstractMaker implements MakerInterface
{

    private MakerInterface $cmsElementResolverMaker;

    public function __construct(
        MakerInterface $cmsElementResolverMaker
    ) {
        $this->cmsElementResolverMaker = $cmsElementResolverMaker;
    }

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {
        $subDirectory = 'Resources/app/administration/src';
        $cmsElementName = $options['elementName'] ?? $input->getArgument('name');

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'elementName' => $cmsElementName,
                'moduleName' => 'sw-cms',
                'mainJsContent' => $this->getMainJsContent($namespaceResolver)
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Element'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $snippetDirectory = 'Resources/app/administration/src/module/';
        $moduleName = 'sw-cms';


        // like: sw-cms.elements.camelCaseCmsElementName.label => YouCustomLabel
        $baseSnippet = [
            'sw-cms' => [
                'elements' => [
                    ( new UnicodeString($options['elementName'] ?? $input->getArgument('name')))->camel()->toString() => [
                        'label' => 'Your Custom Label'
                    ]
                ]
            ]
        ];

        $snippetCollection = $this->makeSnippetFiles($namespaceResolver, $input, ['subDirectory' => $snippetDirectory, 'moduleName' => $moduleName, 'baseSnippet' => json_encode($baseSnippet, JSON_PRETTY_PRINT) ?: '']);
        $storefrontCollection = $this->makeStorefrontElementFile($namespaceResolver, $input, ['elementName' => $cmsElementName]);

        if ($input->getOption('resolver')) {
            $resolver = $this->cmsElementResolverMaker->make($namespaceResolver, $input, ['elementName' => $cmsElementName]);

            return (new DirectoryCollection([$directory]))->merge($snippetCollection)->merge($storefrontCollection)->merge($resolver);
        }

        return (new DirectoryCollection([$directory]))->merge($snippetCollection)->merge($storefrontCollection);
    }

    protected function getMainJsContent(NamespaceResolverInterface $namespaceResolver): string {
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

        if (!$options['elementName']) {
            throw new \RuntimeException('You need to pass a elementName to create snippet files');
        }

        $collection = new DirectoryCollection();
        $subdirectory = 'Resources/views/storefront/element/';
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subdirectory);
        $generator->getParser()->setTemplateData(
            [
                'elementName' => $options['elementName']
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Storefront/Element'), $namespaceResolver->getFullNamespace($subdirectory), $subdirectory);
        $collection->add($directory);
        $generator->generate($directory);

        return $collection;
    }



}
