<?php

namespace Acme\App\Adapter\Console\Batch\SampleQueue02;

use Acme\App\Adapter\Database\Command\Queue02Command;
use Acme\App\Adapter\Database\Dao\Queue02Dao;
use Acme\App\AppContainerHolder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishBatch extends Command
{
    private const PARAM_JOB_IDS = 'job-ids';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('jobQueue02:publish')
            ->setDescription(<<<EOF
MySQLによるキュー実装その2 Publisher
EOF
            )
            ->addArgument(
                static::PARAM_JOB_IDS, InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'jobの種類を示すID', []
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = AppContainerHolder::instance()->loggerApp();
        $dbMaster = AppContainerHolder::instance()->dbMainMaster();
        $cmd = new Queue02Command(new Queue02Dao($dbMaster));

        $enqueues = 0;
        foreach ($input->getArgument('job-ids') as $jobId) {
            $enqueues += $cmd->publishByString($jobId);
        }
        $logger->info(sprintf('enqueued %s rows', $enqueues));
        return 0;
    }
}
