<?php

namespace Pure\PurewareCli\Command\Entity;

use Pure\PurewareCli\Resolver\NamespaceResolverInterface;
use Pure\PurewareCli\Resolver\PluginNamespaceResolver;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MakeEntityCommand extends \Pure\PurewareCli\Command\AbstractMakeCommand
{
    protected static $defaultName = 'make:entity';
    protected NamespaceResolverInterface $pluginNamespaceResolver;

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new entity (definition, entity and collection)')
            ->addArgument('name', InputArgument::REQUIRED, 'The entity name in PascalCase')
            ->addOption('translation', 't', InputOption::VALUE_NONE, 'Generate a translation', null)
            ->addOption('migration', 'm', InputOption::VALUE_NONE, 'Generate a migration file', null)
            ->addOption('hydrator', null, InputOption::VALUE_NONE, 'Generate a EntityHydrator file', null)
            ->addOption('many2many', null, InputOption::VALUE_NONE, 'Generate a ManyToManyAssociation file', null)
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Override files. Be careful when using!')
            ->addOption('workingDir', null, InputOption::VALUE_OPTIONAL, 'The path were you want to create the new plugin', null)
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The table prefix for entity', '');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pluginNamespaceResolver = new PluginNamespaceResolver(); //@todo get the right resolver for plugin
        $composerJson = null;
        if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'composer.json')) {
            $composerJson = file_get_contents(getcwd() . DIRECTORY_SEPARATOR . 'composer.json');
        }

        if (is_null($composerJson)) {
            throw new \RuntimeException('Could not find composer.json of plugin. Run the command in plugin directory.');
        }

        // @todo throw error if not plugin directory
        $pluginNamespaceResolver->resolvePluginNamespace($composerJson);
        //refactor to abstract

        $this->makeEntity($pluginNamespaceResolver, $input);


        return Command::SUCCESS;
    }

    protected function makeEntity(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []) {

        $entityName = $input->getArgument('name');
        $skiPaths = [];
        $treeBuilder = new TreeBuilder();
        $parser = new TwigParser();
        $subDir = $input->getOption('workingDir') ?? 'Content' . DIRECTORY_SEPARATOR . $entityName;
        $generator = new DirectoryGenerator($namespaceResolver->getWorkingDir($subDir), $parser);

        if ($input->getOption('force')) {
            $generator->setForce(true);
        }

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
            $this->makeMigration($namespaceResolver, $input, ['entityName' => $entityName]);
        }
    }

    protected function makeMigration(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []) {
        $parser = new TwigParser();
        $parser->setTemplateData(
            [
                'suffix' => $options['entityName'],
                'timestamp' =>(new \DateTime())->getTimestamp()
            ]
        );

        $generator = new DirectoryGenerator($namespaceResolver->getWorkingDir('Migration'), $parser);

        if ($input->getOption('force')) {
            $generator->setForce(true);
        }

        $directory = (new TreeBuilder())->buildTree(__DIR__ . '/../../Resources/skeleton/migration', $namespaceResolver->getFullNamespace('Migration'));
        $generator->generate($directory);
    }
}
