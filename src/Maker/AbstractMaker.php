<?php

namespace Pureware\PurewareCli\Maker;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Generator\GeneratorInterface;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Symfony\Component\Console\Input\InputInterface;

class AbstractMaker implements \Pureware\PurewareCli\Maker\MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): void
    {
        throw new \RuntimeException('make() method not implemented.');
    }

    protected function getDirectoryGenerator(NamespaceResolverInterface $namespaceResolver,  InputInterface $input, string $directory): GeneratorInterface {
        $generator = new DirectoryGenerator($namespaceResolver->getWorkingDir($directory), new TwigParser());
        $generator->setForce((bool) $input->getOption('force'));

        return $generator;
    }

    protected function getTemplatePath(string $subPath): string {
        return __DIR__ . sprintf('/../Resources/skeleton/%s', $subPath);
    }
}
