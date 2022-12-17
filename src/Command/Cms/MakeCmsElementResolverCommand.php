<?php

namespace Pureware\PurewareCli\Command\Cms;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\Cms\CmsElementResolverMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCmsElementResolverCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:cms-resolver';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the CMS Element yo want to resolve')
            ->setDescription('Create a CMS Data Resolver for a given CMS Element');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new CmsElementResolverMaker())->make($namespaceResolver, $input);
        ServiceTagGenerator::instance()->generate($input, $output, $namespaceResolver);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
