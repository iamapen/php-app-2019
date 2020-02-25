<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

interface PdoInterface extends PdoTransactionInterface
{
    /**
     * @param string $statement
     * @param array|null $driver_options
     * @return PdoStatementInterface
     */
    public function prepare($statement);

    /**
     * @param string $statement
     * @return int
     */
    public function exec(string $statement);

    /**
     * @param string $statement
     * @param string|int $param1
     * @param string|int $param2
     * @param string|int $param3
     * @return PdoStatementInterface
     */
    public function query();

    /**
     * @param string $string
     * @param int|null $parameter_type
     * @return string
     */
    public function quote($string, $parameter_type = null);

    /**
     * @param string|null $seqname
     * @return string
     */
    public function lastInsertId($seqname = null);

    /**
     * @return string|null
     */
    public function errorCode();

    /**
     * @return array
     */
    public function errorInfo();

    /**
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttribute($attribute, $value);

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute($attribute);

    /**
     * @return array
     */
    public static function getAvailableDrivers();
}
