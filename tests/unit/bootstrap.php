<?php declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Log\LogLevel;

/*
 * 環境変数
 */
putenv('APP_ROOT=' . realpath(__DIR__ . '/../../'));
putenv(sprintf('STORAGE_DIR=%s', getenv('APP_ROOT') . '/storage'));
putenv('LOG_DIR=' . getenv('STORAGE_DIR') . '/logs');
putenv('TMP_DIR=' . getenv('STORAGE_DIR') . '/tmp');
putenv('UT_ROOT=' . __DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('APP_ROOT');
$dotenv->required('LOG_DIR');
$dotenv->required('LOG_LEVEL')->allowedValues([
    LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE,
    LogLevel::WARNING, LogLevel::ERROR,
    LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY,
]);

$container = require_once __DIR__ . '/bootstrap/container.php';

setlocale(LC_CTYPE, 'C');

define('UNITTEST_ROOT_DIR', __DIR__);

\Acme\App\AppContainerHolder::init($container);
