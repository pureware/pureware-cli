<?php

namespace Pureware\PurewareCli\Command\CliCommand;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\CliCommand\CliCommandMaker;
use Pureware\PurewareCli\Maker\ScheduledTask\ScheduledTaskMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCliCommandCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:command';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Command')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The vendor prefix for command name in cli', '')
            ->addOption('cliName', 'c', InputOption::VALUE_OPTIONAL, 'The name to run inside the terminal', null)
            ->setDescription('Create a CLI Command');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new CliCommandMaker())->make($namespaceResolver, $input);
        ServiceTagGenerator::instance()->generate($input, $output, $namespaceResolver);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
