<?php

namespace Pureware\PurewareCli\Command\Common;

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
use Symfony\Component\Process\Process;
use Symfony\Component\Serializer\SerializerInterface;

class RunPhpUnitCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    protected static $defaultName = 'test:phpunit';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Run PHP Unit inside the docker container')
            ->addArgument('pluginName', InputArgument::OPTIONAL, 'The name of the plugin you want to test. (optional, default will be the current plugin)', null)
            ->addOption('container', 'c', InputOption::VALUE_OPTIONAL , 'The docker container name', 'shop_plugin')
            ->addOption('options', null, InputOption::VALUE_OPTIONAL  , 'The options that are added to the phpunit command as string i.e.  --options="--testdox"', '--colors=always --testdox');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $pluginName = $input->getArgument('pluginName');
        if (!$input->getArgument('pluginName')) {
            $resolver = $this->getNamespaceResolver();
            $pluginName = $resolver->getPluginName();
        }

        $command = sprintf('docker exec %s vendor/bin/phpunit --configuration="%s" %s', $input->getOption('container'),  'custom/plugins/' . $pluginName, $input->getOption('options'));

        $cli = Process::fromShellCommandline($command, null, null, null, 240);
        $cli->setTty(true);

        $cli->run(function ($type, $line) use ($output) {
            $output->write($line);
        });



        return Command::SUCCESS;
    }

}
