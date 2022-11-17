<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\RouteConfig;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RouteImportGenerator
{
    private static RouteImportGenerator $instance;

    /** @var array<ServiceInterface>  */
    private array $routes = [];

    protected function __construct() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function instance(): RouteImportGenerator
    {
        if (!isset(self::$instance)) {
            self::$instance = new RouteImportGenerator();
        }

        return self::$instance;
    }

    public function generate(InputInterface $input, OutputInterface $output, NamespaceResolverInterface $namespaceResolver): void
    {
        if ($input->getOption('workingDir')) {
            return;
        }
        $path = $namespaceResolver->getWorkingDir('Resources/Config/routes.xml');

        if (!file_exists($path)) {
            $this->createRoutesFile($namespaceResolver);
        }


        $routesFile = file_get_contents($path) ?: throw new \RuntimeException('Could not read routesFile ' . $path);
        $newContent = '';

        foreach ($this->routes as $route) {
            if (str_contains($routesFile, $route->getIdentifier())) {
                // @todo output message route already included
                continue;
            }

            $newContent .= str_repeat(' ', 4) . trim($route->getTemplate());
        }

        if ('' === $newContent) {
            // @todo output message no new route added
            return;
        }

        $endTag = '</routes>';
        $routesFile = str_replace($endTag, $newContent . PHP_EOL . $endTag, $routesFile);

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($path, $routesFile); //@todo catch error
    }

    public function addRoute(ServiceInterface $service): self {
        $this->routes[] = $service;

        return $this;
    }

    private function createRoutesFile(NamespaceResolverInterface $namespaceResolver): void {

        $twig =  new TwigParser();
        $twig->setTemplateData([]);
        $generator = new DirectoryGenerator($namespaceResolver->getWorkingDir('Resources/config'), $twig);

        $templatePath = __DIR__ . sprintf('/../../Resources/skeleton/%s', 'Config/Routes');
        $directory = (new TreeBuilder())->buildTree($templatePath, '');
        $generator->generate($directory);

    }


}
