<?php
namespace Pureware\PurewareCli\Maker\Entity;

use PhpParser\Node\Scalar\MagicConst\Dir;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Shopware\Core\Content\Category\Tree\Tree;
use Symfony\Component\Console\Input\InputInterface;


class EntityMaker extends AbstractMaker implements MakerInterface
{

    private MakerInterface $migrationMaker;

    public function __construct(
        MakerInterface $migrationMaker
    ) {

        $this->migrationMaker = $migrationMaker;
    }

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $entityName = $input->getArgument('name');
        $skiPaths = [];
        $subDir = $input->getOption('workingDir') ?? 'Content' . DIRECTORY_SEPARATOR . $entityName;
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input,  $subDir);
        $parser = $generator->getParser();
        $treeBuilder = new TreeBuilder();

        $parser->setTemplateData(
            [
                'entityName' => $entityName,
                'entityPrefix' => $input->getOption('prefix'),
                'hasTranslation' => (bool) $input->getOption('translation')
            ]
        );

        if (is_null($input->getOption('translation'))) {
            $skiPaths[] = 'Aggregate';
        } else {
            $parser->addTemplateData('parentClassNamespace', $namespaceResolver->getFullNamespace($subDir . DIRECTORY_SEPARATOR . $entityName));
        }
        $treeBuilder->skip($skiPaths);

        $entityDirectory = $treeBuilder->buildTree(__DIR__ . '/../../Resources/skeleton/entity', $namespaceResolver->getFullNamespace($subDir), $entityName);
        $generator->generate($entityDirectory);

        $subDir = new Directory($subDir . DIRECTORY_SEPARATOR . $entityName);
        $subDir->setDirectories(new DirectoryCollection([$entityDirectory]));
        $createdDirectories = new DirectoryCollection([$subDir]);
        if ($input->getOption('migration')) {
            $createdDirectories = $createdDirectories->merge($this->migrationMaker->make($namespaceResolver, $input, array_merge(['suffix' => $entityName], $options)));
        }

        return $createdDirectories;
    }
}
