<?php

namespace Acme\Support\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Stopwatch\Stopwatch;

class StopWatchLoggerTest extends TestCase
{

    public function test__construct()
    {
        $sut = new StopWatchLogger();
        $this->assertInstanceOf(StopWatchLogger::class, $sut);
    }

    public function testGetStopWatch()
    {
        $sut = new StopWatchLogger();
        $this->assertInstanceOf(StopWatchLogger::class, $sut);
        $this->assertInstanceOf(Stopwatch::class, $sut->getStopWatch());
    }

    public function testStart()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('test'))->pushHandler(
            (new StreamHandler($fp))
                ->setFormatter(new LineFormatter('%message%'))
        );
        $sut = new StopWatchLogger(null, $logger);

        $sut->start('foo');
        fseek($fp, 0);
        $this->assertSame('begin: foo', fgets($fp));
    }

    public function testStart_withAdditionalMsg()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('test'))->pushHandler(
            (new StreamHandler($fp))
                ->setFormatter(new LineFormatter('%message%'))
        );
        $sut = new StopWatchLogger(null, $logger);

        $sut->start('foo', 'abc');
        fseek($fp, 0);
        $this->assertSame('begin: foo abc', fgets($fp));
    }


    public function testStop()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('test'))->pushHandler(
            (new StreamHandler($fp))
                ->setFormatter(new LineFormatter('%message%'))
        );
        $sut = new StopWatchLogger(null, $logger);

        $sut->start('foo');
        ftruncate($fp, 0);
        $sut->stop('foo', '10 rows');
        fseek($fp, 0);

        $ex = '|^';
        $ex .= 'end: foo 10 rows elapsed="\d{2}:\d{2}:\d{2}" start="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}" end="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}"';
        $ex .= '$|';
        $this->assertRegExp($ex, fgets($fp));
    }

    public function testLap()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('test'))->pushHandler(
            (new StreamHandler($fp))
                ->setFormatter(new LineFormatter('%message%'))
        );
        $sut = new StopWatchLogger(null, $logger);

        $sut->start('foo');
        ftruncate($fp, 0);
        $sut->lap('foo', '10 rows');
        fseek($fp, 0);

        $ex = '|^';
        $ex .= 'lap: foo 10 rows elapsed="\d{2}:\d{2}:\d{2}" start="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}" end="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}"';
        $ex .= '$|';
        $this->assertRegExp($ex, fgets($fp));
    }
}
