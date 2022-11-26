<?php

namespace Pureware\PurewareCli\Command\Entity;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\Entity\EntityExtensionMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeEntityExtensionCommand extends \Pureware\PurewareCli\Command\AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:entity:extension';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new entity extension')
            ->addArgument('name', InputArgument::REQUIRED, 'The entity name in PascalCase');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new EntityExtensionMaker())->make($namespaceResolver, $input);
        ServiceTagGenerator::instance()->generate($input, $output, $namespaceResolver);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
