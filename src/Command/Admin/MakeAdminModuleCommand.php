<?php

namespace Pureware\PurewareCli\Command\Admin;

use Pureware\PurewareCli\Command\AbstractMakeCommand;
use Pureware\PurewareCli\Maker\Admin\AdminComponentMaker;
use Pureware\PurewareCli\Maker\Admin\AdminModuleMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeAdminModuleCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:admin-module';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new Admin Module')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Admin Module in PascalCase or camelCase')
            ->addOption('prefix', null, InputOption::VALUE_REQUIRED, 'The prefix for the module', '')
            ->addOption('navigationParent', null, InputOption::VALUE_REQUIRED, 'The menu entry you want to add link the new module', 'sw-catalogue')
            ->addOption('moduleColor', null, InputOption::VALUE_OPTIONAL, 'The color for the module', '#ff3d58')
            ->addOption('snippetLanguages', 's', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Additional migration name', ['de-DE', 'en-GB']);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new AdminModuleMaker())->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
