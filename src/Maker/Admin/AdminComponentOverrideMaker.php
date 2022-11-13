<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Admin;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class AdminComponentOverrideMaker extends AbstractMaker implements MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {
        $subDirectory = 'Resources/app/administration/src';

        $componentName = $input->getArgument('name');

        if (!$componentName) {
            throw new \RuntimeException('You need a componentName to override a component.');
        }

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'componentName' => $componentName,
                'mainJsContent' => $this->getMainJsContent($namespaceResolver)
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Admin/ComponentOverride'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        return (new DirectoryCollection([$directory]));
    }

    protected function getMainJsContent(NamespaceResolverInterface $namespaceResolver): string {
        $content = '';

        if (file_exists($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'))) {
            $content = file_get_contents($namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js'));
        }

        return is_string($content) ? $content : '';
    }

}
