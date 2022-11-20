<?php

namespace Pureware\PurewareCli\Tests\Plugin;

use Pureware\PurewareCli\Resolver\NamespaceResolverInterface;
use Pureware\PurewareCli\Resolver\PluginNamespaceResolver;

class PluginNamespaceResolverTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Pureware\PurewareCli\Resolver\NamespaceResolverInterface
     */
    protected $resolver;

    public function setUp(): void
    {
        parent::setUp();
        $this->resolver = new PluginNamespaceResolver();
    }

    public function test_plugin_base_namespace()
    {
        $composerJson = '{"name":"pure/new-plugin","type":"shopware-platform-plugin","require":{},"autoload":{"psr-4":{"Pure\\NewPlugin\\":"src/"}},"extra":{}}';

        $this->resolver->resolvePluginNamespace($composerJson);

        $pluginBaseNamespace = 'Pure\NewPlugin';
        $pluginWorkingDir = getcwd() . DIRECTORY_SEPARATOR . 'src';
        $this->assertEquals($pluginBaseNamespace, $this->resolver->getFullNamespace());
        $this->assertEquals($pluginBaseNamespace . '\\Aggregate\\Translations', $this->resolver->getFullNamespace('Aggregate\Translations'));
        $this->assertEquals($pluginWorkingDir,  $this->resolver->getWorkingDir());
    }

    public function test_plugin_namespace_for_different_composer_types()
    {
        $composerJson = '{"name":"pure/new-plugin","type":"different-type","require":{},"autoload":{"psr-4":{"Pure\\NewPlugin\\":"src/"}},"extra":{}}';
        $this->expectException(\RuntimeException::class);
        $this->resolver->resolvePluginNamespace($composerJson);
    }
}
