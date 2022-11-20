<?php

namespace Pureware\PurewareCli\Command\Migration;

use Pureware\PurewareCli\Maker\Migration\MigrationMaker;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\UnicodeString;

class MakeMigrationCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:migration';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new migration')
            ->addArgument('suffix', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Additional migration name', []);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $input->setOption('force', true);
        $suffix = (new UnicodeString(implode(' ', $input->getArgument('suffix'))))->camel()->title();
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new MigrationMaker())->make($namespaceResolver, $input, [
            'suffix' => $suffix,
        ]);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
