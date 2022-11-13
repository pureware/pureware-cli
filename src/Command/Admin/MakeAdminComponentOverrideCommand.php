<?php

namespace Pureware\PurewareCli\Command\Admin;

use Pureware\PurewareCli\Command\AbstractMakeCommand;
use Pureware\PurewareCli\Maker\Admin\AdminComponentMaker;
use Pureware\PurewareCli\Maker\Admin\AdminComponentOverrideMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeAdminComponentOverrideCommand extends AbstractMakeCommand
{
    /** @var string */
    protected static $defaultName = 'make:admin-component-override';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Override a shopware admin component')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Component you want to override i.e. sw-dashboard-index');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new AdminComponentOverrideMaker())->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
