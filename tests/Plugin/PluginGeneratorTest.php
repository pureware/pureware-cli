<?php

namespace Pureware\PurewareCli\Tests\Plugin;

use PHPUnit\Framework\TestCase;
use Pureware\PurewareCli\Command\Generators\NewPluginCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PluginGeneratorTest extends TestCase
{
    public function test_command_creates_new_plugin()
    {
        $testPluginName = 'TestFancyPluginName';
        $executeDirectory = __DIR__ . '/../../test_output';
        $fullPath = $executeDirectory . DIRECTORY_SEPARATOR . $testPluginName;

        if (file_exists($fullPath)) {
            exec('rm -rf ' . $fullPath);
        }

        $this->assertDirectoryDoesNotExist($fullPath, 'Plugin still exist');


        $application = new Application();
        $application->add(new NewPluginCommand());
        $command = $application->find('new:plugin');
        $command = new CommandTester($command);
        $command->execute(
            [
                'pluginName' => $testPluginName,
                '--workingDir' => $executeDirectory,
                '--git' => true,
                '--quiet' => true,
                '--no-interaction' => true
            ]
        );

        $this->assertDirectoryExists($fullPath, 'Plugin dir does not exists');
        $this->assertDirectoryExists($fullPath . DIRECTORY_SEPARATOR . '.git', 'Git dir does not exists');

    }

    public function test_command_creates_new_plugin_with_shopware_version()
    {
        $testPluginName = 'TestFancyPluginNameWithVersion';
        $executeDirectory = __DIR__ . '/../../test_output';
        $fullPath = $executeDirectory . DIRECTORY_SEPARATOR . $testPluginName;

        if (file_exists($fullPath)) {
            exec('rm -rf ' . $fullPath);
        }

        $this->assertDirectoryDoesNotExist($fullPath, 'Plugin still exist');


        $application = new Application();
        $application->add(new NewPluginCommand());
        $command = $application->find('new:plugin');
        $command = new CommandTester($command);
        $command->execute(
            [
                'pluginName' => $testPluginName,
                '--workingDir' => $executeDirectory,
                '--git' => true,
                '--quiet' => true,
                '--shopwareVersion' => '6.4.15.1'
            ]
        );

        $this->assertDirectoryExists($fullPath, 'Plugin dir does not exists');
        $this->assertDirectoryExists($fullPath . DIRECTORY_SEPARATOR . '.git', 'Git dir does not exists');

    }
}
