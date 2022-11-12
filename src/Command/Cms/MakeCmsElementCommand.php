<?php

namespace Pureware\PurewareCli\Command\Cms;

use Pureware\PurewareCli\Maker\Cms\CmsElementMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCmsElementCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /** @var string */
    protected static $defaultName = 'make:cms-element';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the CMS Element')
            ->setDescription('Create new CMS Element')
            ->addOption('resolver', 'r', InputOption::VALUE_NONE, 'Generate ams resolver', null)
            ->addOption('snippetLanguages', 's',InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Additional migration name', ['de-DE', 'en-EN']);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $input->setOption('force', true);
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new CmsElementMaker())->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);


        return Command::SUCCESS;
    }
}
