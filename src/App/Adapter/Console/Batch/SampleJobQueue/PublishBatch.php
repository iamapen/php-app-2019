<?php

namespace Acme\App\Adapter\Console\Batch\SampleJobQueue;

use Acme\App\Adapter\Database\Command\Queue01Command;
use Acme\App\Adapter\Database\Dao\Queue01Dao;
use Acme\App\AppContainerHolder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishBatch extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('jobQueue:publish')
            ->setDescription('MySQLによるキュー実装のPublisher')
            ->addArgument(
                'job-ids', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'job_id', []
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = AppContainerHolder::instance()->loggerApp();
        $dbMaster = AppContainerHolder::instance()->dbMainMaster();
        $cmd = new Queue01Command(new Queue01Dao($dbMaster));

        var_dump($input->getArgument('job-ids'));

        $enqueues = 0;
        foreach ($input->getArgument('job-ids') as $jobId) {
            $enqueues += $cmd->publish($jobId);
        }
        $logger->info(sprintf('enqueued %s rows', $enqueues));
        return 0;
    }
}
