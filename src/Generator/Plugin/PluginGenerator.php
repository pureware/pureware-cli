<?php

namespace Pureware\PurewareCli\Generator\Plugin;

use Pureware\PurewareCli\Generator\GeneratorInterface;
use Pureware\TemplateGenerator\Generator\DirectoryGenerator;
use Pureware\TemplateGenerator\Parser\TwigParser;
use Pureware\TemplateGenerator\TreeBuilder\TreeBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

class PluginGenerator implements GeneratorInterface
{
    public const DEFAULT_VERSION = '6.4.15.0';
    private ?string $pluginName = null;
    private ?string $workingDir = null;
    private string $namespace;
    private string $composerName;
    private string $shopwareVersion;

    public function generate(Input $input, Output $output): int
    {
        $this->pluginName = $input->getArgument('pluginName');

        $io = new SymfonyStyle($input, $output);
        $io->progressStart(4);
        if (!$this->pluginName) {
            $this->pluginName = $io->ask('Name of the plugin');
        }

        $this->shopwareVersion = $input->getOption('shopwareVersion') ?? $this->getLatestShopwareVersion();
        $dockwareVersion = $this->getDockwareVersion($input->getOption('shopwareVersion'));
        if (version_compare($this->shopwareVersion, $dockwareVersion, '==') === false) {
            $io->warning(sprintf('Could not match the dockware version for shopware v%s. Latest dockware is %s', $this->shopwareVersion, $dockwareVersion));
            if ($io->askQuestion(new ConfirmationQuestion('Do you want to choose a dockware and shopware version manually'))) {
                $dockwareVersion = $io->ask('Input a dockware and shopware version', $dockwareVersion);
                $this->shopwareVersion = $dockwareVersion;
            }
        }

        if (version_compare($this->shopwareVersion, '6', '>=') === false) {
            throw new \RuntimeException('The Plugin Generator only works for shopware 6');
        }

        $this->resolveNamespace();

        $this->namespace = $io->ask('Base namespace', $this->namespace); /** @todo validate input */

        $workingDir = $input->getOption('workingDir') ?? getcwd();
        $this->workingDir = rtrim($workingDir, DIRECTORY_SEPARATOR);
        $pluginPath = $workingDir . DIRECTORY_SEPARATOR . $this->pluginName;

        $parser = new TwigParser();
        $parser->setTemplateData(
            [
                'pluginName' => $this->pluginName,
                'namespace' => $this->namespace,
                'composerName' => $this->composerName,
                'copyright' => '',
                'composerDescriptionEn' => $this->pluginName,
                'composerDescriptionDe' => $this->pluginName,
                'phpVersion' => version_compare('6.5', $this->shopwareVersion) > 0 ? '^7.4.3 || ^8.0' : '^8.0',
                'dockwarePhpVersion' => version_compare('6.5', $this->shopwareVersion) > 0 ? '7.4' : '8.0',
                'shopwareVersion' => $this->shopwareVersion,
                'dockwareVersion' => $dockwareVersion,
                'containerName' => 'shop_plugin'
            ]
        );
        $io->progressAdvance(1);

        $generator = new DirectoryGenerator($pluginPath, $parser);
        if ($input->getOption('force')) {
            $generator->setForce(true);
        }

        $directory = (new TreeBuilder())->buildTree(__DIR__ . '/../../Resources/skeleton/Plugin', $this->pluginName);
        $io->progressAdvance(1);

        $generator->generate($directory);

        $commands = [
            $this->findComposer() . ' install --working-dir=' . $pluginPath,
            sprintf('echo "%s"', 'PURE installed composer dependencies'),
            sprintf('ls -la %s', $pluginPath)
        ];
        $this->executeCommands($commands, $output);
        $io->progressAdvance(1);
        $output->writeln('');

        $messages = [
            '',
            sprintf(' ✓ %s %s: %s', $this->pluginName, 'Plugin created. Change directory', str_replace($_SERVER['HOME'], '~', $pluginPath)),
            '✓ Installed composer dependencies'
        ];

        if ($input->getOption('git')) {
            $this->initGit($output, $input->getOption('branch'));
            $messages[] = '✓ init git. Dont forget to set remote url.';
        }

        $io->success($messages);

        return Command::SUCCESS;

    }

    public function resolveNamespace(): void {

        $snakeCase = (new UnicodeString($this->pluginName))->camel()->title()->snake();
        $strings = explode('_', $snakeCase);
        if (count($strings) < 2) {
            throw new \RuntimeException('Could not resolve a namespace for this plugin name. Provide a name with a prefix i.e. SwagPlugin');
        }

        $prefix = \ucfirst(\array_shift($strings));
        $class = (new UnicodeString(implode("_", $strings)))->camel()->title();

        $this->namespace = $prefix . "\\" . $class;

        $this->composerName = \strtolower($prefix) . "/" . (new AsciiSlugger())->slug($class->snake());
    }

    protected function findComposer(): string
    {
        $composerPath = getcwd() . '/composer.phar';

        if (file_exists($composerPath)) {
            return '"'.PHP_BINARY.'" '.$composerPath;
        }

        return 'composer';
    }

    private function initGit(OutputInterface $output, string $branch): void {
        if (file_exists($this->workingDir . DIRECTORY_SEPARATOR . $this->pluginName . DIRECTORY_SEPARATOR . '.git')) {
            $output->write('Git already exists. Skipping.');
            return;
        }

        $commands = [
            'cd ' . $this->workingDir . DIRECTORY_SEPARATOR . $this->pluginName,
            'git init',
            'git add .',
            'git commit -m "PURE shopware plugin"',
            "git branch -M {$branch}",
        ];

        $this->executeCommands($commands, $output);
    }

    protected function executeCommands($commands, OutputInterface $output): Process
    {
        $cli = Process::fromShellCommandline(implode(' && ', $commands));
        $cli->setTty(true);

        $cli->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        return $cli;
    }

    protected function getLatestShopwareVersion(): string {

        try {
            $client = new \GuzzleHttp\Client();
            $get = $client->get('https://api.github.com/repos/shopware/platform/releases/latest', [
                'content-type' => 'application/json'
            ]);
            $content = $get->getBody()->getContents();
            $json = json_decode($content, true);
            return str_replace('v', '', $json['tag_name']);

        } catch (\Exception $exception) {
           return self::DEFAULT_VERSION;
        }
    }

    /**
     * @param string|null $inputShopwareVersion | if version is not set or not found the latest version tag is returned
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getDockwareVersion(?string $inputShopwareVersion = null): string
    {

        $client = new \GuzzleHttp\Client();
        $url = 'https://hub.docker.com/v2/repositories/dockware/dev/tags/?page_size=1&page=1';

        if (!is_null($inputShopwareVersion)) {
            $url  .= '&name=' . $inputShopwareVersion;
        }

        $get = $client->get($url, [
            'content-type' => 'application/json'
        ]);
        $content = $get->getBody()->getContents();
        $json = json_decode($content, true);

        if (empty($json['results'])) {
            return $this->getDockwareVersion();
        }

        return $json['results'][0]['name'];
    }
}
