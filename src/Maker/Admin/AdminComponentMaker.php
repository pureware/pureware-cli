<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Admin;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class AdminComponentMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = $input->getOption('workingDir') ?? $options['workingDir'] ?? 'Resources/app/administration/src';

        $componentName = $options['componentName'] ?? $input->getArgument('name');
        $moduleName = $options['moduleName'] ?? $input->getOption('module');

        if (! $componentName || ! $moduleName) {
            throw new \RuntimeException('You need a componentName and a moduleName to create a component.');
        }

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'moduleName' => $moduleName,
                'componentName' => $componentName,
                'mainJsContent' => $this->getMainJsContent($namespaceResolver),
            ]
        );

        $templatePath = 'Admin/Component';
        if ($options['componentsOnly']) {
            $templatePath .= '/module/{{moduleName}}/components';
        }

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath($templatePath), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        return (new DirectoryCollection([$directory]));
    }

    protected function getMainJsContent(NamespaceResolverInterface $namespaceResolver): string
    {
        $content = '';

        if (file_exists($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'))) {
            $content = file_get_contents($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'));
        }

        return is_string($content) ? $content : '';
    }
}
