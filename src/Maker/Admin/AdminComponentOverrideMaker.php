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

class AdminComponentOverrideMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = 'Resources/app/administration/src';

        $componentName = $input->getArgument('name');

        if (! $componentName) {
            throw new \RuntimeException('You need a componentName to override a component.');
        }

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'componentName' => $componentName,
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Admin/ComponentOverride'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        $importComponentName = (new AsciiSlugger())->slug((new UnicodeString($componentName))->snake()->toString())->toString();
        MainJsImportGenerator::instance()->addImport(
            (new MainJsImportFactory())->createImport(sprintf('module/%s-override', $importComponentName))
        );

        return (new DirectoryCollection([$directory]));
    }
}
