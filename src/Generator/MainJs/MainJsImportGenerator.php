<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\MainJs;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MainJsImportGenerator
{
    private static MainJsImportGenerator $instance;

    /**
     * @var array<ImportInterface>
     */
    private array $imports = [];

    protected function __construct()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function instance(): MainJsImportGenerator
    {
        if (! isset(self::$instance)) {
            self::$instance = new MainJsImportGenerator();
        }

        return self::$instance;
    }

    public function generate(InputInterface $input, OutputInterface $output, NamespaceResolverInterface $namespaceResolver): void
    {
        $path = $namespaceResolver->getWorkingDir('Resources/app/administration/src/main.js');
        // @todo output message that trying to add import
        $fileSystem = new Filesystem();

        if (! file_exists($path)) {
            $fileSystem->touch($path);
            return;
        }

        if (file_get_contents($path)) {
            $mainJsFileContent = file_get_contents($path);
        } else {
            throw new \RuntimeException('Could not read servicesFile ' . $path);
        }
        $newContent = '';

        foreach ($this->imports as $import) {
            if (str_contains($mainJsFileContent, $import->getIdentifier())) {
                // @todo output message service tag already included
                continue;
            }

            $newContent .= trim($import->getTemplate());
        }

        if ('' === $newContent) {
            // @todo output message not new service added
            return;
        }

        $content = $mainJsFileContent . PHP_EOL . $newContent;

        $fileSystem->dumpFile($path, $content);
    }

    public function addImport(ImportInterface $service): self
    {
        $this->imports[] = $service;

        return $this;
    }
}
