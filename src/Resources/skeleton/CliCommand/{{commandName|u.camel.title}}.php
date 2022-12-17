<?php declare(strict_types=1);

namespace {{fileNamespace}};

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class {{commandName|u.camel.title}} extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = '{{cliCommandName}}';

    protected function configure(): void
    {
        $this->setName('{{cliCommandName}}');
        $this->setDescription('Custom Command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }

}
