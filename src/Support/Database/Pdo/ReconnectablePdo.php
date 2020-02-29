<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class ReconnectablePdo implements PdoInterface
{
    use Reconnectable;

    /**
     * @param PdoInterface $pdo
     * @param array $pdoOptions
     * @param mixed ...$constructorArgs PDOのコンストラクタに渡す引数
     */
    private function __construct(PdoInterface $pdo, array $pdoOptions, ...$constructorArgs)
    {
        $this->pdo = $pdo;
        $this->initReconnectable($constructorArgs, $pdoOptions, get_class($pdo));
    }

    /**
     * 作成済みのPDOインスタンスから作成する
     * @param PdoInterface $pdo
     * @param array $pdoOptions
     * @param mixed ...$constructorArgs PDOのコンストラクタに渡す引数
     * @return static
     */
    public static function createByPdo(PdoInterface $pdo, array $pdoOptions = [], ...$constructorArgs)
    {
        return new static($pdo, $pdoOptions, ...$constructorArgs);
    }

    /**
     * @param string $pdoClassName
     * @param array $pdoOptions
     * @param mixed ...$constructorArgs PDOのコンストラクタに渡す引数
     * @return static
     */
    public static function createByClassName(string $pdoClassName, array $pdoOptions = [], ...$constructorArgs)
    {
        $pdo = new $pdoClassName(...$constructorArgs);

        if (!($pdo instanceof PdoInterface)) {
            throw new \InvalidArgumentException(sprintf(
                'pdoClassName must be a %s implemented class name, "%s" given',
                PdoInterface::class,
                $pdoClassName
            ));
        }

        return new static($pdo, $pdoOptions, ...$constructorArgs);
    }

    public function prepare($statement)
    {
        return $this->pdo->prepare(...func_get_args());
    }

    public function exec(string $statement)
    {
        return $this->pdo->exec(...func_get_args());
    }

    public function query()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return $this->pdo->query(...func_get_args());
    }

    public function quote($string, $parameter_type = null)
    {
        return $this->pdo->quote(...func_get_args());
    }

    public function lastInsertId($seqname = null)
    {
        return $this->pdo->lastInsertId(...func_get_args());
    }

    public function errorCode()
    {
        return $this->pdo->errorCode();
    }

    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }

    public function setAttribute($attribute, $value)
    {
        return $this->pdo->setAttribute(...func_get_args());
    }

    public function getAttribute($attribute)
    {
        return $this->pdo->getAttribute(...func_get_args());
    }

    public static function getAvailableDrivers()
    {
        return Pdo::getAvailableDrivers();
    }

    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollback();
    }
}
