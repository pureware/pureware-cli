<?php

namespace Pureware\PurewareCli\Command;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\File\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractMakeCommand extends Command
{
    protected function configure()
    {
        $this
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Override files. Be careful when using!')
            ->addOption('workingDir', null, InputOption::VALUE_OPTIONAL, 'The path where you want to create the new plugin', null);
        parent::configure();
    }

    protected function getNamespaceResolver(): NamespaceResolverInterface
    {
        $pluginNamespaceResolver = new PluginNamespaceResolver();
        $composerJson = null;
        $composerJsonPath = getcwd() . DIRECTORY_SEPARATOR . 'composer.json';

        if (file_exists($composerJsonPath)) {
            $composerJson = file_get_contents($composerJsonPath);
        }

        if (is_null($composerJson) || $composerJson === false) {
            throw new \RuntimeException(sprintf('Could not find composer.json in %s. Run the command in plugin directory.', $composerJsonPath));
        }

        $pluginNamespaceResolver->resolvePluginNamespace($composerJson);

        return $pluginNamespaceResolver;
    }

    protected function renderMaker(DirectoryCollection $dirs, InputInterface $input, OutputInterface $output, NamespaceResolverInterface $namespaceResolver): void
    {
        $io = new SymfonyStyle($input, $output);
        $workingPath = $namespaceResolver->getWorkingDir();
        $home = $_SERVER['HOME'];

        $io->title('Files created in: ' . str_replace($home, '~', $workingPath));

        foreach ($dirs as $dir) {
            $this->addFile($io, $dir->getName());
            $this->renderDir($dir, $io);
        }
    }

    protected function renderDir(Directory $directory, SymfonyStyle $io, int $depth = 0): void
    {
        /** @var File $file */
        foreach ($directory->getFiles() as $file) {
            $this->addFile($io, $file->getParsedFileName(), $depth + 1);
        }

        if ($directory->getDirectories() === null) {
            return;
        }

        foreach ($directory->getDirectories()->getIterator() as $dir) {
            $this->renderDir($dir, $io, $depth + 1);
        }
    }

    protected function addFile(SymfonyStyle $io, string $path, int $depth = 0): void
    {
        $space = str_repeat("  ", $depth);
        $io->writeln(sprintf('|%s -- %s', $space, $path));
    }
}
