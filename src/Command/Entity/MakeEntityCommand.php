<?php

namespace Pureware\PurewareCli\Command\Entity;

use Pureware\PurewareCli\Maker\Entity\EntityMaker;
use Pureware\PurewareCli\Maker\Migration\MigrationMaker;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MakeEntityCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    protected static $defaultName = 'make:entity';

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
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The table prefix for entity', '');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        (new EntityMaker(new MigrationMaker()))->make($this->getNamespaceResolver(), $input);

        return Command::SUCCESS;
    }
}
