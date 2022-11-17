<?php

namespace Pureware\PurewareCli\Command\Entity;

use Pureware\PurewareCli\Maker\Entity\EntityMaker;
use Pureware\PurewareCli\Maker\Entity\HydratorMaker;
use Pureware\PurewareCli\Maker\Entity\Many2ManyMaker;
use Pureware\PurewareCli\Maker\Entity\TranslationMaker;
use Pureware\PurewareCli\Maker\Migration\MigrationMaker;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\File\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

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
            ->addOption('many2many', null, InputOption::VALUE_NONE, 'Generate a MappingEntityDefinition file for a ManyToManyAssociation', null)
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The table prefix for entity', '');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new EntityMaker(new MigrationMaker(), new HydratorMaker(), new Many2ManyMaker(), new TranslationMaker()))->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }

}
