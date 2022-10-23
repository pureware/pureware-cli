<?php
namespace Pureware\PurewareCli\Command;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractMakeCommand extends Command
{

    protected function configure()
    {
        $this
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Override files. Be careful when using!')
            ->addOption('workingDir', null, InputOption::VALUE_OPTIONAL, 'The path were you want to create the new plugin', null);
        parent::configure();
    }

    protected function getNamespaceResolver(): NamespaceResolverInterface {
        $pluginNamespaceResolver = new PluginNamespaceResolver();
        $composerJson = null;
        $composerJsonPath = getcwd() . DIRECTORY_SEPARATOR . 'composer.json';

        if (file_exists($composerJsonPath)) {
            $composerJson = file_get_contents($composerJsonPath);
        }

        if (is_null($composerJson)) {
            throw new \RuntimeException(sprintf('Could not find composer.json in %s. Run the command in plugin directory.', $composerJsonPath));
        }

        $pluginNamespaceResolver->resolvePluginNamespace($composerJson);

        return $pluginNamespaceResolver;
    }

}
