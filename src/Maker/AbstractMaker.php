<?php

namespace Pureware\PurewareCli\Maker;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Generator\GeneratorInterface;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\File\File;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;

class AbstractMaker implements \Pureware\PurewareCli\Maker\MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        throw new \RuntimeException('make() method not implemented.');
    }

    protected function getDirectoryGenerator(NamespaceResolverInterface $namespaceResolver,  InputInterface $input, string $directory): GeneratorInterface {
        $generator = new DirectoryGenerator($namespaceResolver->getWorkingDir($directory), new TwigParser());
        $generator->setForce((bool) $input->getOption('force'));

        return $generator;
    }

    protected function getTemplatePath(string $subPath): string {
        return __DIR__ . sprintf('/../Resources/skeleton/%s', $subPath);
    }

    /**
     * @param array<string> $options
     */
    protected function makeSnippetFiles(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        if (!$options['subDirectory']) {
            throw new \RuntimeException('You need to pass a subDirectory to create snippet files');
        }

        if (!$options['moduleName']) {
            throw new \RuntimeException('You need to pass a moduleName to create snippet files');
        }

        $collection = new DirectoryCollection();

        foreach ($input->getOption('snippetLanguages') as $locale) {
            $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $options['subDirectory']);
            $generator->getParser()->setTemplateData(
                [
                    'locale' => $locale,
                    'moduleName' => $options['moduleName'],
                    'baseSnippet' => $options['baseSnippet'] ?? ''
                ]
            );
            $generator->setForce(false); // do not override snippet files because the< share data between different components

            $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Cms/Snippet'), $namespaceResolver->getFullNamespace($options['subDirectory']), $options['subDirectory']);
            $collection->add($directory);
            $generator->generate($directory);
        }

        return $collection;
    }

    /**
     * @param InputInterface $input
     * @param array<string|bool> $options
     * @param string|null $default
     * @return string
     */
    protected function getSubDirectory(InputInterface $input, array $options, ?string $default = null): string {
        $subDirectory = $input->getOption('workingDir') ?? $options['workingDir'] ?? $default;

        if (!$subDirectory) {
            throw new \RuntimeException('You need to pass a workingDir');
        }

        return $subDirectory;
    }
}
