<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Maker\Controller;

use Pureware\PurewareCli\Generator\ContainerConfig\ServiceFactory;
use Pureware\PurewareCli\Generator\ContainerConfig\ServiceTagGenerator;
use Pureware\PurewareCli\Generator\RouteConfig\RouteFactory;
use Pureware\PurewareCli\Generator\RouteConfig\RouteImportGenerator;
use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

class ControllerMaker extends AbstractMaker implements MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection {
        $routeScope = $input->getOption('routeScope') ?? $options['routeScope'];
        $httpMethod = $input->getOption('method') ?? $options['method'];

        if (!in_array($routeScope, ['storefront', 'api'])) {
            throw new \RuntimeException('Route Scope has to be storefront or api');
        }

        if (!in_array($httpMethod, [ 'GET', 'POST', 'PUT', 'HEAD', 'DELETE', 'PATCH', 'OPTIONS', 'CONNECT', 'TRACE'])) {
            throw new \RuntimeException('Unknown http method passed.');
        }

        $defaultDirectory = $routeScope === 'storefront' ? 'Storefront/Controller' : 'Api/Controller';
        $subDirectory = $input->getOption('workingDir') ?? $options['workingDir'] ?? $defaultDirectory;

        $controllerName = $options['controllerName'] ?? $input->getArgument('name');

        if (!$controllerName) {
            throw new \RuntimeException('You need a controller name to creat a controller.');
        }

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);

        $isStorefront = $routeScope === 'storefront';
        $basicRoute = $input->getOption('basicRoute') ?? $options['basicRoutes'];
        $routeName = $isStorefront ? 'frontend' : 'api';
        $routeName .= '-' . (new UnicodeString($basicRoute))->camel()->toString();
        $routeName = (new AsciiSlugger())->slug($routeName, '.');
        $generator->getParser()->setTemplateData(
            [
                'controllerName' => $controllerName . 'Controller',
                'routeScope' => $routeScope,
                'method' => $httpMethod,
                'basicRoute' => $basicRoute,
                'routeName' => $routeName,
                'isAjax' => $input->getOption('isAjax'),
                'isStorefront' => $isStorefront
            ]
        );

        $directory = (new TreeBuilder())->buildTree($this->getTemplatePath('Controller'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($directory);

        ServiceTagGenerator::instance()->addService(
            (new ServiceFactory())->generateServiceController($namespaceResolver->getFullNamespace($subDirectory . '/' . $controllerName . 'Controller'))
        );

        if (!$input->getOption('workingDir')) {
            $route = $isStorefront ? '../../Storefront/Controller/*Controller.php' : '../../Api/Controller/*Controller.php';
            RouteImportGenerator::instance()->addRoute(
                (new RouteFactory())->generateRoute($route)
            );
        }

        return (new DirectoryCollection([$directory]));
    }

}
