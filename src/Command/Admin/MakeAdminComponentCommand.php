<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Command\Admin;

use Pureware\PurewareCli\Command\AbstractMakeCommand;
use Pureware\PurewareCli\Generator\MainJs\MainJsImportGenerator;
use Pureware\PurewareCli\Maker\Admin\AdminComponentMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeAdminComponentCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:admin-component';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new component for a given Admin Module')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Admin Component in PascalCase or camelCase')
            ->addOption('module', 'm', InputOption::VALUE_REQUIRED, 'Name of a given module like sw-cms', null);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new AdminComponentMaker())->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        MainJsImportGenerator::instance()->generate($input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
