<?php declare(strict_types=1);

namespace Acme\Test\Fake;

use Throwable;

/**
 * mysqlのタイムアウトが起きたこと表現する例外のfake
 * \PDOException::getCode() が final でmock不可のためfake
 */
class FakeMysqlTimeoutException extends \PDOException
{
    public function __construct(
        $message = "SQLSTATE[HY000]: General error: 2006 MySQL server has gone away",
        $code = 'HY000',
        Throwable $previous = null
    )
    {
        parent::__construct($message, 0, $previous);

        $this->code = $code;
        $this->errorInfo = ['HY000', 2006, 'MySQL server has gone away'];
    }
}
