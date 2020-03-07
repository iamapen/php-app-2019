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
     * タイムアウトしているかどうかチェック、必要な場合は再接続する
     * @return bool 再接続したかどうか
     */
    public function reconnectIfTimeouted(): bool
    {
        try {
            $this->query('SELECT 1 FROM dual');
        } catch (\PDOException $e) {
            if (false === MySqlErrorChecker::isTimeOutException($e)) {
                throw $e;
            }
            $this->reconnect();
            return true;
        }
        return false;
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
