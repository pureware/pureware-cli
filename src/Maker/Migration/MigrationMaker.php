<?php

namespace Pureware\PurewareCli\Maker\Migration;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\Directory\Directory;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class MigrationMaker extends AbstractMaker implements MakerInterface
{
    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): DirectoryCollection
    {
        $subDirectory = 'Migration';

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);

        $generator->getParser()->setTemplateData(
            [
                'suffix' => $options['suffix'] ?? '',
                'timestamp' => $options['timestamp'] ?? $this->getTimestamp(),
            ]
        );

        $migrationDirectory = (new TreeBuilder())->buildTree($this->getTemplatePath('Migration'), $namespaceResolver->getFullNamespace($subDirectory), $subDirectory);
        $generator->generate($migrationDirectory);

        return new DirectoryCollection([$migrationDirectory]);
    }

    protected function getTimestamp(): int
    {
        return (new \DateTime())->getTimestamp();
    }
}
