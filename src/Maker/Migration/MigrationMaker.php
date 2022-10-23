<?php

namespace Pureware\PurewareCli\Maker\Migration;

use Pureware\PurewareCli\Maker\AbstractMaker;
use Pureware\PurewareCli\Maker\MakerInterface;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Input\InputInterface;

class MigrationMaker extends AbstractMaker implements MakerInterface
{

    public function make(NamespaceResolverInterface $namespaceResolver, InputInterface $input, array $options = []): void {
        $subDirectory = 'Migration';

        $generator = $this->getDirectoryGenerator($namespaceResolver, $input, $subDirectory);

        $generator->getParser()->setTemplateData(
            [
                'suffix' => $options['suffix'] ?? '',
                'timestamp' =>(new \DateTime())->getTimestamp()
            ]
        );

        $generator->generate((new TreeBuilder())->buildTree($this->getTemplatePath('migration'), $namespaceResolver->getFullNamespace($subDirectory)));
    }
}
