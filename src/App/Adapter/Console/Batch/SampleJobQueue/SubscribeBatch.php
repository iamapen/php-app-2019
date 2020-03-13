<?php

namespace Acme\App\Adapter\Console\Batch\SampleJobQueue;

use Acme\App\Adapter\Database\Command\Queue01Command;
use Acme\App\Adapter\Database\Dao\Queue01Dao;
use Acme\App\AppContainerHolder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeBatch extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('jobQueue:subscribe')
            ->setDescription('MySQLによるキュー実装のSubscriber');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = AppContainerHolder::instance()->loggerApp();
        $dbMaster = AppContainerHolder::instance()->dbMainMaster();
        $cmd = new Queue01Command(new Queue01Dao($dbMaster));

        $jobs = $cmd->subscribe();
        $logger->info(sprintf("%s messages found", count($jobs)));
        foreach ($jobs as $job) {
            $logger->info(sprintf(
                "id=%s job_id=%s created=%s\n", $job['id'], $job['job_id'], $job['created_at']
            ));
        }

        $cmd->subscribeDone();
        return 0;
    }
}
