<?php declare(strict_types=1);
/**
 * localstack でAmazon-SQSを試す
 */
require __DIR__ . '/../../bootstrap/bootstrap.php';
require __DIR__ . '/../../bootstrap/container.php';

use Aws\Sqs\SqsClient;

$container = \Acme\App\AppContainerHolder::instance();
$logger = $container->loggerCli();

$endpoint = 'http://localhost:4576';
$queueUrl = sprintf('%s%s', $endpoint, '/queue/sample-jobs');

$client = new SqsClient([
    'region' => 'dummy-sqs',
    'version' => '2012-11-05',
    'endpoint' => $endpoint,
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key' => 'dummy',
        'secret' => 'dummy',
    ],
]);

$count = 10;
for ($i = 0; $i < $count; $i++) {
    $client->sendMessageAsync([
        'QueueUrl' => $queueUrl,
        'MessageBody' => 'this is body' . $i,
        'MessageGroupId' => 'testGroup',
    ]);
}
