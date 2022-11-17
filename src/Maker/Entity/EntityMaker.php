<?php

namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Shopware\Core\Content\Category\Tree\Tree;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

class EntityMaker extends AbstractMaker implements MakerInterface
{
    private MakerInterface $migrationMaker;

    private MakerInterface $hydratorMaker;

    private MakerInterface $many2manyMaker;

    private MakerInterface $translationMaker;

    public function __construct(
        MakerInterface $migrationMaker,
        MakerInterface $hydratorMaker,
        MakerInterface $many2manyMaker,
        MakerInterface $translationMaker
    ) {
        $this->migrationMaker = $migrationMaker;
        $this->hydratorMaker = $hydratorMaker;
        $this->many2manyMaker = $many2manyMaker;
        $this->translationMaker = $translationMaker;
    }

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $entityName = $input->getArgument('name');
        $skiPaths = [
            '{{entityName|u.camel.title}}Hydrator.php',
            '{{entityName|u.camel.title}}MappingDefinition.php',
            'Aggregate',
        ];
        $subDirPath = $input->getOption('workingDir') ?? 'Content' . DIRECTORY_SEPARATOR . $entityName;
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirPath);
        $parser = $generator->getParser();
        $treeBuilder = new TreeBuilder();

        $parser->setTemplateData(
            [
                'entityName' => $entityName,
                'entityPrefix' => $input->getOption('prefix'),
                'hasTranslation' => (bool) $input->getOption('translation'),
            ]
        );

        $treeBuilder->skip($skiPaths);

        $entityDirectory = $treeBuilder->buildTree(__DIR__ . '/../../Resources/skeleton/entity', $namespaceResolver->getFullNamespace($subDirPath), $entityName);
        $generator->generate($entityDirectory);

        $subDir = new Directory($subDirPath);
        $subDir->setDirectories(new DirectoryCollection([$entityDirectory]));
        $createdDirectories = new DirectoryCollection([$subDir]);

        if ($input->getOption('translation')) {
            $createdDirectories = $createdDirectories->merge($this->translationMaker->make($namespaceResolver, $input, array_merge([
                'entityName' => $entityName,
                'entityPrefix' => $input->getOption('prefix'),
                'workingDir' => $subDirPath . DIRECTORY_SEPARATOR . 'Aggregate' . DIRECTORY_SEPARATOR . 'Translation',
                'parentClass' => $namespaceResolver->getFullNamespace($subDirPath . DIRECTORY_SEPARATOR . $entityName),
            ], $options)));
        }

        if ($input->getOption('migration')) {
            $createdDirectories = $createdDirectories->merge($this->migrationMaker->make($namespaceResolver, $input, array_merge([
                'suffix' => $entityName,
            ], $options)));
        }

        if ($input->getOption('hydrator')) {
            $createdDirectories = $createdDirectories->merge($this->hydratorMaker->make($namespaceResolver, $input, array_merge([
                'entityName' => $entityName,
                'workingDir' => $subDirPath,
            ], $options)));
        }

        if ($input->getOption('many2many')) {
            $createdDirectories = $createdDirectories->merge($this->many2manyMaker->make($namespaceResolver, $input, array_merge([
                'entityName' => $entityName,
                'entityPrefix' => $input->getOption('prefix'),
                'workingDir' => $subDirPath,
            ], $options)));
        }

        return $createdDirectories;
    }
}
