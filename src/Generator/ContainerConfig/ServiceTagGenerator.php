<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\ContainerConfig;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ServiceTagGenerator
{

    private static ServiceTagGenerator $instance;

    /** @var array<ServiceInterface>  */
    private array $services = [];

    protected function __construct() {}
    protected function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function instance(): ServiceTagGenerator
    {
        if (!isset(self::$instance)) {
            self::$instance = new ServiceTagGenerator();
        }

        return self::$instance;
    }

    public function generate(InputInterface $input, OutputInterface $output, NamespaceResolverInterface $namespaceResolver): void
    {
        $services = $this->services;

        $path = $namespaceResolver->getWorkingDir('Resources/Config/services.xml');
        // @todo output message that trying to add service tag in this file

        if (false === file_exists($path)) {
            return;
        }

        $servicesFile = file_get_contents($path) ?: throw new \RuntimeException('Could not read servicesFile ' . $path);
        $newContent = '';

        foreach ($this->services as $service) {
            if (str_contains($servicesFile, $service->getIdentifier())) {
                // @todo output message service tag already included
                continue;
            }

            $newContent .= str_repeat(' ', 4) . trim($service->getTemplate());
        }

        if ('' === $newContent) {
            // @todo output message not new service added
            return;
        }

        $endTag = '</services>';
        $servicesFile = str_replace($endTag, $newContent . PHP_EOL . PHP_EOL . str_repeat(' ', 4) . $endTag, $servicesFile);

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($path, $servicesFile); //@todo catch error

    }

    public function addService(ServiceInterface $service): self {
        $this->services[] = $service;

        return $this;
    }

}