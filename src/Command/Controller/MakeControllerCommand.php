<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Command\Controller;

use Pureware\PurewareCli\Command\AbstractMakeCommand;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Generator\RouteConfig\RouteImportGenerator;
use Pureware\PurewareCli\Maker\Controller\ControllerMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:controller';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create new controller either for storefront or admin api')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Controller')
            ->addOption('routeScope', 's', InputOption::VALUE_REQUIRED, 'Name of  the routeScope. Can be storefront or api', 'storefront')
            ->addOption('basicRoute', 'r', InputOption::VALUE_REQUIRED, 'Name of a route inside the controller', 'exampleRoute')
            ->addOption('method', 'm', InputOption::VALUE_REQUIRED, 'Http Method. Can be:  GET, POST, PUT, HEAD, DELETE, PATCH, OPTIONS, CONNECT, TRACE', 'GET')
            ->addOption('isAjax', 'x', InputOption::VALUE_NONE, 'Add XmlHttpRequest to annotation');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespaceResolver = $this->getNamespaceResolver();
        $dirs = (new ControllerMaker())->make($namespaceResolver, $input);
        ServiceTagGenerator::instance()->generate($input, $output, $namespaceResolver);
        RouteImportGenerator::instance()->generate($input, $output, $namespaceResolver);
        $this->renderMaker($dirs, $input, $output, $namespaceResolver);

        return Command::SUCCESS;
    }
}
