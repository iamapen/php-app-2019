<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

/**
 * 再接続可能なPDO Trait
 */
trait Reconnectable
{
    /** @var array */
    private $pdoConstructorArgs = [];
    /** @var string */
    private $pdoClassName;

    /** @var PdoInterface */
    private $pdo;
    /** @var array */
    private $pdoOpts = [];


    protected function initReconnectable(array $pdoConstructorArgs, array $pdoOpts, string $pdoClassname)
    {
        $this->pdoConstructorArgs = $pdoConstructorArgs;
        $this->pdoOpts = $pdoOpts;
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
        if ($e->errorInfo[0] !== 'HY000') {
            return false;
        }
        if ($e->errorInfo[1] !== 2006) {
            return false;
        }
        //if ($e->errorInfo[2] !== 'MySQL server has gone away') {
        //    return false;
        //}
        return true;
    }

    /**
     * @return PdoInterface
     */
    protected function newConnection(): PdoInterface
    {
        /* @var PdoInterface $newPdo */
        $newPdo = new $this->pdoClassName(...$this->pdoConstructorArgs);
        foreach ($this->pdoOpts as $name => $val) {
            $newPdo->setAttribute($name, $val);
        }
        return $newPdo;
    }

    /**
     * 再接続する
     */
    public function reconnect()
    {
        $this->pdo = $this->newConnection();
    }
}
