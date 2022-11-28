<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\ScheduledTask;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceFactory;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

class ScheduledTaskMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = $this->getSubDirectory($input, $options, 'Service/ScheduledTask');

        $taskName = (new UnicodeString($input->getArgument('name')))->camel()->title()->toString();
        $taskName = preg_replace('/Task$/', '', $taskName) . 'Task';

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'taskName' => $taskName,
                'prefix' => $input->getOption('prefix'),
                'defaultInterval' => $input->getOption('interval'),
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('ScheduledTask'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        ServiceTagGenerator::instance()->addService(
            (new ServiceFactory())->generateScheduledTask(
                $namespaceResolver->getFullNamespace($subDirectory . '/' . $taskName),
                $namespaceResolver->getFullNamespace($subDirectory . '/' . $taskName . 'Handler')
            )
        );

        return (new DirectoryCollection([$directory]));
    }
}
