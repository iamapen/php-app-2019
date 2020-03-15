<?php

namespace Acme\App\Adapter\Console\Batch\SampleJobQueue;

use Acme\App\Adapter\Database\Command\Queue01Command;
use Acme\App\Adapter\Database\Dao\Queue01Dao;
use Acme\App\AppContainerHolder;
use Acme\Support\Lock\FLocker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeBatch extends Command
{
    private const EXIT_LOCK_FAILED = 1;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('jobQueue01:subscribe')
            ->setDescription(<<<EOF
MySQLによるキュー実装その1 Subscriber
キューテーブルをごっそり入れ替えてsnapshotを作り、
snapshotの全件を順次処理、snapshotテーブルをdropするパターン
EOF
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = AppContainerHolder::instance()->loggerApp();

        $locker = $this->createLocker();
        if (!$locker->getLock()) {
            $logger->debug('lock failed.');
            return static::EXIT_LOCK_FAILED;
        }

        $dbMaster = AppContainerHolder::instance()->dbMainMaster();
        $cmd = new Queue01Command(new Queue01Dao($dbMaster));

        // テーブルからsnapshotを作り、全件取得
        $jobs = $cmd->subscribe();
        $logger->info(sprintf("%s messages found", count($jobs)));
        foreach ($jobs as $job) {
            $logger->info(sprintf(
                "取得job: id=%s job_id=%s created=%s\n", $job['id'], $job['job_id'], $job['created_at']
            ));
        }

        // トランザクションはここから
        $dbMaster->beginTransaction();
        // ここで何らかの処理をして...
        $dbMaster->commit();

        // dequeueはsnapshotのDROP TABLE
        // subscriberが何らかの理由でエラー終了した場合、snapshotテーブルが残る
        // 手動でsnapshotを戻したり消したりしない限り、次のsubscriberは起動できない
        $cmd->dequeue();

        $locker->unLockAndClose();
        return 0;
    }

    private function createLocker()
    {
        $lockfile = sprintf('%s/%s', getenv('LOCK_DIR'), $this->getName());
        return FLocker::ofFullpath($lockfile);
    }
}
