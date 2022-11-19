<?php

namespace Pureware\PurewareCli\Maker\Entity;

use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class TranslationMaker extends EntityDependentMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = $this->getSubDirectory($input, $options);
        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);

        $generator->getParser()->setTemplateData(
            [
                'entityName' => $options['entityName'] ?? $input->getOption('entityName'),
                'entityPrefix' => $options['entityPrefix'] ?? $input->getOption('prefix'),
                'parentClassNamespace' => $this->resolveParentClassNamespace($namespaceResolver, $input, $options),
            ]
        );

        $hydratorDirectory = (new TreeBuilder())->buildTree($this->getTemplatePath('Entity/Aggregate/Translation'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($hydratorDirectory);

        return new DirectoryCollection([$hydratorDirectory]);
    }

    private function resolveParentClassNamespace(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options): string
    {
        if ($input->hasOption('parentClass') && $input->getOption('parentClass')) {
            return $namespaceResolver->getFullNamespace($input->getOption('parentClass'));
        } else {
            if ($options['parentClass']) {
                return $options['parentClass'];
            } else {
                throw new \RuntimeException('You need to pass a parentClass definition. e.g. Content/EntityName/EntityNameDefinition');
            }
        }
    }
}
