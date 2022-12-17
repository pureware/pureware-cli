<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\CliCommand;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceFactory;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

class CliCommandMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = $this->getSubDirectory($input, $options, 'Command');

        $commandName = (new UnicodeString($input->getArgument('name')))->camel()->title()->toString();
        $commandName = preg_replace('/Command$/', '', $commandName);

        if (is_null($commandName)) {
            throw new \RuntimeException('Could not find command name');
        }

        if ($input->getOption('cliName')) {
            $cliCommandName = (new AsciiSlugger())->slug((new UnicodeString($input->getOption('cliName')))->snake()->toString());
        } else {
            $cliCommandName = (new AsciiSlugger())->slug((new UnicodeString($commandName))->snake()->toString());

            if ($input->getOption('prefix')) {
                $cliCommandName = $input->getOption('prefix') . ':' . $cliCommandName;
            }
        }
        $commandName .= 'Command';

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);
        $generator->getParser()->setTemplateData(
            [
                'commandName' => $commandName,
                'cliCommandName' => $cliCommandName,
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('CliCommand'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        ServiceTagGenerator::instance()->addService(
            (new ServiceFactory())->generateServiceTag(
                $namespaceResolver->getFullNamespace($subDirectory . '/' . $commandName),
                'console.command'
            )
        );

        return (new DirectoryCollection([$directory]));
    }
}
