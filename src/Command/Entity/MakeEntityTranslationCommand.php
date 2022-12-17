<?php

namespace Pureware\PurewareCli\Command\Entity;

use Pureware\PurewareCli\Maker\Entity\EntityMaker;
use Pureware\PurewareCli\Maker\Entity\HydratorMaker;
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

class MakeEntityTranslationCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:entity:translation';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create a TranslationEntity for a given entity')
            ->addArgument('parentEntity', InputArgument::REQUIRED, 'The entity path or namespace e.g. Content/Entity/EntityName')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The table prefix for entity', '')
            ->addOption('aggregate', 'a', InputOption::VALUE_NONE, 'Create aggregate sub directory inside parentEntity');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new TranslationMaker())->make($namespaceResolver, $input, [
            'parentClass' => $namespaceResolver->getFullNamespace($input->getArgument('parentEntity')),
            'entityName' => $this->resolveEntityName($input, $namespaceResolver),
            'workingDir' => $this->resolveWorkingDir($input, $namespaceResolver),
            'entityPrefix' => $input->getOption('prefix'),
        ]);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }

    protected function resolveEntityName(InputInterface $input, NamespaceResolverInterface $namespaceResolver): string
    {
        $path = $namespaceResolver->getWorkingDir($input->getArgument('parentEntity'));
        return basename($path);
    }

    protected function resolveWorkingDir(InputInterface $input, NamespaceResolverInterface $namespaceResolver): string
    {
        if ($input->getOption('workingDir')) {
            return $namespaceResolver->getWorkingDir($input->getOption('workingDir'));
        }

        $inputEntity = str_replace('\\', '/', $input->getArgument('parentEntity'));
        $directory = explode('/', $inputEntity);
        array_pop($directory);
        $entityDirectory = implode('/', $directory);

        if ($input->getOption('aggregate')) {
            return $entityDirectory . DIRECTORY_SEPARATOR . 'Aggregate' . DIRECTORY_SEPARATOR . 'Translation';
        }

        return $entityDirectory;
    }
}
