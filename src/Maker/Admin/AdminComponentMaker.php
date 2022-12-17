<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Admin;

use Pureware\PurewareCli\Generator\MainJs\MainJsImportFactory;
use Pureware\PurewareCli\Generator\MainJs\MainJsImportGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

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
            ]
        );

        $templatePath = 'Admin/Component';
        if ($options['componentsOnly']) {
            $templatePath .= '/module/{{moduleName}}/components';
        }

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath($templatePath), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $importModuleName = (new AsciiSlugger())->slug((new UnicodeString($moduleName))->snake()->toString())->toString();
        $importComponentName = (new AsciiSlugger())->slug((new UnicodeString($componentName))->snake()->toString())->toString();

        MainJsImportGenerator::instance()->addImport(
            (new MainJsImportFactory())->createImport(sprintf('module/%s/components/%s', $importModuleName, $importComponentName))
        );

        return (new DirectoryCollection([$directory]));
    }
}
