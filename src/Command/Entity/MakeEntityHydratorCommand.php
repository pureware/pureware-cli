<?php

namespace Pureware\PurewareCli\Command\Entity;

use Pureware\PurewareCli\Maker\Entity\EntityMaker;
use Pureware\PurewareCli\Maker\Entity\HydratorMaker;
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

class MakeEntityHydratorCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    protected static $defaultName = 'make:entity:hydrator';

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create a EntityHydrator for a given entity')
            ->addArgument('name', InputArgument::REQUIRED, 'The entity name in PascalCase');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new HydratorMaker())->make($namespaceResolver, $input, [
            'entityName' => $input->getArgument('name'),
            'workingDir' => $input->getOption('workingDir')
        ]);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }

}
