#!/usr/bin/env php
<?php
/**
 * CLIのエントリポイント
 */
declare(strict_types=1);

/* @var $app Symfony\Component\Console\Application */
$app = require __DIR__ . '/../bootstrap/console.php';
$app->run();
