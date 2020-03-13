<?php declare(strict_types=1);

/**
 * CLI bootstrap
 * @return \Symfony\Component\Console\Application
 */

use Acme\App\Adapter\Console\Batch as Batch;

require __DIR__ . '/bootstrap.php';

$app = new Acme\App\Adapter\Console\Application(
    require __DIR__ . '/container.php'
);
$app->registerErrorHandler();

// バッチコマンド登録
$app->registerCommand(Batch\HelloCommand::class);
$app->registerCommand(Batch\ChainCommand::class);

$app->registerCommand(Batch\SampleJobQueue\SubscribeBatch::class);
$app->registerCommand(Batch\SampleJobQueue\PublishBatch::class);

return $app;
