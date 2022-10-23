<?php
namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Maker\Migration\MigrationMaker;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;


class EntityMaker extends AbstractMaker implements MakerInterface
{

    private MigrationMaker $migrationMaker;

    public function __construct(
        MigrationMaker $migrationMaker
    ) {

        $this->migrationMaker = $migrationMaker;
    }

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): void
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

        $directory = $treeBuilder->buildTree(__DIR__ . '/../../Resources/skeleton/entity', $namespaceResolver->getFullNamespace($subDir), $entityName);
        $generator->generate($directory);

        if ($input->getOption('migration')) {
            $this->migrationMaker->make($namespaceResolver, $input, ['suffix' => $entityName]);
        }
    }
}
