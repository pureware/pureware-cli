<?php

namespace Pureware\PurewareCli\Command\New;

use Pureware\PurewareCli\Generator\Plugin\PluginGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewPluginCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('new:plugin')
            ->addArgument('pluginName', InputArgument::OPTIONAL, 'The name of a plugin', null)
            ->addOption('shopwareVersion', 's', InputArgument::OPTIONAL, sprintf('Shopware version i.e. %s. If not set the latest Shopware tag will be used', PluginGenerator::DEFAULT_VERSION), null)
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Override files. Be careful when using!')
            ->addOption('workingDir', null, InputOption::VALUE_OPTIONAL, 'The path were you want to create the new plugin', null)
            ->addOption('git', null, InputOption::VALUE_NONE, 'Initialize a git repo and first commit with a branch. Set a remote url afterwards.')
            ->addOption('branch', null, InputOption::VALUE_OPTIONAL, 'Init branch name for git', 'main')
            ->setDescription('Create a new standalone plugin with a ready to use boilerplate.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(PHP_EOL . "<fg=blue>
 _____  _    _ _____  ______ 
|  __ \| |  | |  __ \|  ____|
| |__) | |  | | |__) | |__   
|  ___/| |  | |  _  /|  __|  
| |    | |__| | | \ \| |____ 
|_|     \____/|_|  \_\______| </>" . PHP_EOL . PHP_EOL);

        $output->writeln('Pure installer');

        return (new PluginGenerator())->generate($input, $output);
    }
}
