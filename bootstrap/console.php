<?php declare(strict_types=1);
/**
 * CLI bootstrap
 * @return \Symfony\Component\Console\Application
 */

require __DIR__ . '/bootstrap.php';

$app = new Acme\App\Adapter\Console\Application(
    require __DIR__ . '/container.php'
);
$app->registerErrorHandler();

// バッチコマンド登録
$app->registerCommand(\Acme\App\Adapter\Console\Command\HelloCommand::class);
$app->registerCommand(\Acme\App\Adapter\Console\Command\ChainCommand::class);

return $app;
