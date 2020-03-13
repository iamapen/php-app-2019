<?php
declare(strict_types=1);

namespace Acme\App\Adapter\Console\Batch;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * バッチからバッチを呼ぶ例
 */
class ChainCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app2019:chain')
            ->setDescription('バッチからバッチを呼ぶ例');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helloCommand = new HelloCommand();
        $helloInput = new ArrayInput(['outDir' => getenv('TMP_DIR')], $helloCommand->getDefinition());

        return $helloCommand->run($helloInput, $output);
    }
}
