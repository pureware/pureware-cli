<?php

namespace Pureware\PurewareCli\Command\ScheduledTask;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\ScheduledTask\ScheduledTaskMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeScheduledTaskCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:scheduled-task';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Scheduled Task')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'The vendor prefix to your custom task, to prevent collisions with other plugins scheduled tasks', '')
            ->addOption('interval', null, InputOption::VALUE_REQUIRED, 'The interval in seconds at which your scheduled task should be executed', '300')
            ->setDescription('Create a Scheduled Task and a Scheduled Task Handler');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new ScheduledTaskMaker())->make($namespaceResolver, $input);
        ServiceTagGenerator::instance()->generate($input, $output, $namespaceResolver);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
