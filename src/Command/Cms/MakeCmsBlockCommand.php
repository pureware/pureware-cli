<?php

namespace Pureware\PurewareCli\Command\Cms;

use Pureware\PurewareCli\Command\AbstractMakeCommand;
use Pureware\PurewareCli\Maker\Cms\CmsBlockMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCmsBlockCommand extends AbstractMakeCommand
{
    /** @var string */
    protected static $defaultName = 'make:cms-block';

    protected function configure(): void
    {
        $categories = 'commerce, form, image, sidebar, text-image, text and video';
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new CMS Block')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the CMS Block')
            ->addOption('category', 'c',InputOption::VALUE_OPTIONAL, 'Choose one Category for the CMS Block ' . $categories, 'commerce')
            ->addOption('snippetLanguages', 's',InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Additional migration name', ['de-DE', 'en-GB']);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new CmsBlockMaker())->make($namespaceResolver, $input);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
