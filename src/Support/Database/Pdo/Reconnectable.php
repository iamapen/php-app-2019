<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

/**
 * 再接続可能なPDO　Trait
 */
trait Reconnectable
{
    /** @var array */
    private $pdoConstructorArgs = [];
    /** @var string */
    private $pdoClassName;


    protected function initReconnectable(array $pdoConstructorArgs, string $pdoClassname)
    {
        $this->pdoConstructorArgs = $pdoConstructorArgs;
        $this->pdoClassName = $pdoClassname;
    }

    /**
     * サーバサイドタイムアウト(MySQL server has gone away) の例外かどうかを返す
     * @param \PDOException $e
     * @return bool
     */
    public static function isTimeOutException(\PDOException $e): bool
    {
        if ($e->getCode() !== 'HY000') {
            return false;
        }
        if ($e->errorInfo[1] !== 2006) {
            return false;
        }
        return true;
    }

    /**
     * @return PdoInterface
     */
    protected function newConnection(): PdoInterface
    {
        return new $this->pdoClassName(...$this->pdoConstructorArgs);
    }
}
