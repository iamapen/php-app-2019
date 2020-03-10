<?php declare(strict_types=1);

namespace Acme\Support\Log;

use Acme\Support\DateTime\DateTimeUtility;
use Symfony\Component\Stopwatch\Stopwatch;

class StopWatchLogger
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;
    /** @var Stopwatch */
    private $stopWatch;

    public function __construct(StopWatch $stopWatch = null, \Psr\Log\LoggerInterface $logger = null)
    {
        if ($stopWatch === null) {
            $stopWatch = new Stopwatch();
        }
        $this->stopWatch = $stopWatch;

        if ($logger === null) {
            $logger = new \Psr\Log\NullLogger();
        }
        $this->logger = $logger;
    }

    /**
     * @return Stopwatch
     */
    public function getStopWatch()
    {
        return $this->stopWatch;
    }

    /**
     * 開始
     * @param string $name
     * @param string|null $additionalMsg 追加で出力したいメッセージ
     * @param string|null $category
     */
    public function start($name, $additionalMsg = null, $category = null)
    {
        $this->stopWatch->start($name, $category);

        $msg = $name;
        if (strval($additionalMsg) !== '') {
            $msg .= ' ' . $additionalMsg;
        }
        $this->logger->info('begin: ' . $msg);
    }

    /**
     * 終了
     * @param string $name
     * @param string|null $additionalMsg 追加で出力したいメッセージ
     */
    public function stop($name, $additionalMsg = null)
    {
        $ev = $this->stopWatch->stop($name);

        $msg = $name;
        if (strval($additionalMsg) !== '') {
            $msg .= ' ' . $additionalMsg;
        }
        $this->logger->info(sprintf(
            'end: %s elapsed="%s" start="%s" end="%s"',
            $msg,
            DateTimeUtility::secondsToHms(floor($ev->getDuration() / 1000)),
            date('Y-m-d H:i:s', (int)floor(($ev->getOrigin() + $ev->getStartTime()) / 1000)),
            date('Y-m-d H:i:s', (int)floor(($ev->getOrigin() + $ev->getEndTime()) / 1000)),
        ));
    }

    /**
     * 終了
     * @param string $name
     * @param string|null $additionalMsg 追加で出力したいメッセージ
     */
    public function lap($name, $additionalMsg = null)
    {
        $ev = $this->stopWatch->lap($name);

        $msg = $name;
        if (strval($additionalMsg) !== '') {
            $msg .= ' ' . $additionalMsg;
        }
        $this->logger->info(sprintf(
            'lap: %s elapsed="%s" start="%s" end="%s"',
            $msg,
            DateTimeUtility::secondsToHms(floor($ev->getDuration() / 1000)),
            date('Y-m-d H:i:s', (int)floor(($ev->getOrigin() + $ev->getStartTime()) / 1000)),
            date('Y-m-d H:i:s', (int)floor(($ev->getOrigin() + $ev->getEndTime()) / 1000)),
        ));
    }
}
