<?php

namespace Acme\App\Adapter\Console\Batch\SampleQueue02;

use Acme\App\Adapter\Database\Command\Queue02Command;
use Acme\App\Adapter\Database\Dao\Queue02Dao;
use Acme\App\AppContainerHolder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeBatch extends Command
{
    private const PARAM_JOB_ID = 'job-id';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('jobQueue02:subscribe')
            ->setDescription(<<<EOF
MySQLによるキュー実装その2 Subscriber
ジョブを1件ずつlockして取得、処理、dequeueするパターン
EOF
            )
            ->addArgument(
                static::PARAM_JOB_ID, InputArgument::REQUIRED,
                'jobの種類を示すID'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = AppContainerHolder::instance()->loggerApp();
        $dbMaster = AppContainerHolder::instance()->dbMainMaster();
        $cmd = new Queue02Command(new Queue02Dao($dbMaster));

        // 低負荷でatomicなロックをするために、トランザクション外で行う必要がある
        // メッセージを実行中にマークし、取得する
        $job = $cmd->subscribeByString($input->getArgument(static::PARAM_JOB_ID));
        if ($job === null) {
            $logger->info('jobがありません');
            return 0;
        }

        $logger->info(sprintf(
            "取得job: id=%s job_id=%s created=%s\n",
            $job['id'], $job['job_id'],
            $job['created_at']
        ));

        // トランザクションはここから
        $dbMaster->beginTransaction();
        // ここで何らかの処理をして...
        $dbMaster->commit();

        // dequeueもトランザクション外
        // subscriberが何らかの理由でエラー終了した場合、メッセージは実行中のまま残る
        // 必要であれば別途クリーンアップのバッチを用意する
        $deleteAffects = $cmd->dequeueByMsgId($job['id'], $job['job_id']);
        $logger->info(sprintf("dequeued %s rows", $deleteAffects));

        return 0;
    }
}
