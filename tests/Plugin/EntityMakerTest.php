<?php

namespace Pureware\PurewareCli\Tests\Plugin;

use PHPUnit\Framework\TestCase;
use Pureware\PurewareCli\Maker\Entity\EntityMaker;
use Pureware\PurewareCli\Maker\Entity\HydratorMaker;
use Pureware\PurewareCli\Maker\Entity\Many2ManyMaker;
use Pureware\PurewareCli\Maker\Entity\TranslationMaker;
use Pureware\PurewareCli\Maker\Migration\MigrationMaker;
use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;
use Pureware\TemplateGenerator\TreeBuilder\Directory\DirectoryCollection;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class EntityMakerTest extends TestCase
{

    /**
     * @var string
     */
    protected $testDirectory = 'test_output/TestPlugin/src';

    public function test_command_creates_new_entity(): void
    {

        if (!file_exists(__DIR__ . '/../../TestPlugin')) {
            $fileSystem = new Filesystem();
            $fileSystem->mkdir(__DIR__ . '/../../' . $this->testDirectory);
        }

        $maker = new EntityMaker(new MigrationMaker(), new HydratorMaker(), new Many2ManyMaker(), new TranslationMaker());
        $namespaceResolver = $this->getNamespaceResolver();
        $input = $this->getInputInterface();
        $input->setInteractive(false);
        $maker->make($namespaceResolver, $input, ['timestamp' => '1667133679']);

        $testDirectory = __DIR__ . '/../../' . $this->testDirectory;
        $paths = [
            '/Content/FantasyName/FantasyNameEntity.php',
            '/Content/FantasyName/FantasyNameDefinition.php',
            '/Content/FantasyName/FantasyNameCollection.php',
            '/Content/FantasyName/Aggregate/Translation/FantasyNameTranslationEntity.php',
            '/Content/FantasyName/Aggregate/Translation/FantasyNameTranslationDefinition.php',
            '/Content/FantasyName/Aggregate/Translation/FantasyNameTranslationCollection.php',
            '/Migration/Migration1667133679FantasyName.php',
        ];

        foreach ($paths as $path) {
            $dirPath = $testDirectory . $path;
            $this->assertFileExists($dirPath, sprintf('The directory %s does not exist', $dirPath));
        }

    }

    private function getNamespaceResolver(): NamespaceResolverInterface {
        $resolver = new PluginNamespaceResolver();
        $resolver->resolvePluginNamespace('{"name":"pure/new-plugin","type":"shopware-platform-plugin","require":{},"autoload":{"psr-4":{"Pure\\NewPlugin\\":"' . $this->testDirectory .'"}},"extra":{}}');

        return $resolver;
    }

    protected function getInputInterface(): InputInterface
    {
        $input = $this->createMock(InputInterface::class);
        $input->method('getOption')->will(
            $this->returnCallback(function ($arg) {
                switch ($arg) {
                    case 'translation':
                    case 'force':
                    case 'migration':
                        return true;
                    case 'prefix':
                        return 'pure';
                    default:
                        return null;
                }
            })
        );

        $input->method('getArgument')->will(
            $this->returnCallback(function ($arg) {
                switch ($arg) {
                    case 'name':
                        return 'FantasyName';
                    default:
                        return null;
                }
            })
        );

        return $input;
    }
}
