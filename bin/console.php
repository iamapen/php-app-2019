#!/usr/bin/env php
<?php
/**
 * CLIのエントリポイント
 */
declare(strict_types=1);

/* @var $app \Acme\App\Adapter\Console\Application */
$app = require __DIR__ . '/../bootstrap/console.php';
$app->run();
