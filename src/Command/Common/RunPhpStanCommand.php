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

class RunPhpStanCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'phpstan';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Run PHP stan for src and tests')
            ->addOption('options', null, InputOption::VALUE_OPTIONAL, 'The options that are added to the phpstan command as string i.e.  --options="-c phpstan.neo"', '-c phpstan.neon');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = sprintf('vendor/bin/phpstan analyse src Tests -c phpstan.neon %s', $input->getOption('options'));

        $cli = Process::fromShellCommandline($command, null, null, null, 240);
        $cli->setTty(true);

        $cli->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        return Command::SUCCESS;
    }
}
