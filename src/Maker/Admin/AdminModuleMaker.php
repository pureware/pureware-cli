<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Admin;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

class AdminModuleMaker extends AbstractMaker implements MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {
        $subDirectory = 'Resources/app/administration/src';
        $moduleName = $input->getArgument('name');
        $prefixedModuleName = $input->getOption('prefix') ? $input->getOption('prefix') . '-' . $input->getArgument('name') : $input->getArgument('name');
        $prefixedModuleName = (new AsciiSlugger())->slug((new UnicodeString($prefixedModuleName))->snake()->toString())->toString();

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'prefixedModuleName' => $prefixedModuleName,
                'moduleName' => $moduleName,
                'prefix' => $input->getOption('prefix'),
                'moduleColor' => $input->getOption('moduleColor'),
                'navigationParent' => $input->getOption('navigationParent'),
                'mainJsContent' => $this->getMainJsContent($namespaceResolver),
                'snippetLanguages' => $input->getOption('snippetLanguages')
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Admin/Module'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $workingDir = $subDirectory . '/module/' . $prefixedModuleName . '/page';

        $listPage = (new AdminComponentMaker())->make($namespaceResolver, $input, [
            'moduleName' => $prefixedModuleName,
            'componentName' => $prefixedModuleName . '-list',
            'componentsOnly' => true,
            'workingDir' => $workingDir
        ]);

        $detailPage = (new AdminComponentMaker())->make($namespaceResolver, $input, [
            'moduleName' => $prefixedModuleName,
            'componentName' => $prefixedModuleName . '-detail',
            'componentsOnly' => true,
            'workingDir' => $workingDir
        ]);

        $createPage = (new AdminComponentMaker())->make($namespaceResolver, $input, [
            'moduleName' => $prefixedModuleName,
            'componentName' => $prefixedModuleName . '-create',
            'componentsOnly' => true,
            'workingDir' => $workingDir
        ]);

        $snippetData = [
            $prefixedModuleName => [
                'general' => [
                    'mainMenuItemGeneral' => 'PURE Menu Item'
                ]
            ]
        ];

        $snippets = $this->makeSnippetFiles($namespaceResolver, $input, [
            'subDirectory' => $subDirectory . '/module/',
            'moduleName' => $prefixedModuleName,
            'baseSnippet' => json_encode($snippetData, JSON_PRETTY_PRINT) ?: ''
        ]);

        return (new DirectoryCollection([$directory]))->merge($listPage)->merge($detailPage)->merge($createPage)->merge($snippets);
    }

    protected function getMainJsContent(NamespaceResolverInterface $namespaceResolver): string {
        $content = '';

        if (file_exists($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'))) {
            $content = file_get_contents($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'));
        }

        return is_string($content) ? $content : '';
    }

}
