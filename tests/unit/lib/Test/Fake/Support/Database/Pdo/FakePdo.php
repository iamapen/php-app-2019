<?php declare(strict_types=1);

namespace Acme\Test\Fake\Support\Database\Pdo;

use Acme\Support\Database\Pdo\PdoInterface;

class FakePdo implements PdoInterface
{
    /** @var array */
    public $arrMsg = [];

    /** @var array */
    public static $staticArrMsg;

    public function prepare($statement)
    {
        $this->arrMsg[] = __FUNCTION__;

        $fake = new FakePdoStatement();
        $fake->str = __FUNCTION__;
        return $fake;
    }

    public function exec(string $statement)
    {
        $this->arrMsg[] = __FUNCTION__;
        return 100;
    }

    public function query()
    {
        $this->arrMsg[] = __FUNCTION__;

        $fake = new FakePdoStatement();
        $fake->str = __FUNCTION__;
        return $fake;
    }

    public function quote($string, $parameter_type = null)
    {
        $this->arrMsg[] = __FUNCTION__;
        return __FUNCTION__;
    }

    public function lastInsertId($seqname = null)
    {
        $this->arrMsg[] = __FUNCTION__;
        return __FUNCTION__;
    }

    public function errorCode()
    {
        $this->arrMsg[] = __FUNCTION__;
        return __FUNCTION__;
    }

    public function errorInfo()
    {
        $this->arrMsg[] = __FUNCTION__;
        return [__FUNCTION__];
    }

    public function setAttribute($attribute, $value)
    {
        $this->arrMsg[] = __FUNCTION__;
        return true;
    }

    public function getAttribute($attribute)
    {
        $this->arrMsg[] = __FUNCTION__;
        return __FUNCTION__;
    }

    public static function getAvailableDrivers()
    {
        static::$staticArrMsg[] = __FUNCTION__;
        return [__FUNCTION__];
    }

    public function inTransaction()
    {
        $this->arrMsg[] = __FUNCTION__;
        return true;
    }

    public function beginTransaction()
    {
        $this->arrMsg[] = __FUNCTION__;
        return true;
    }

    public function commit()
    {
        $this->arrMsg[] = __FUNCTION__;
        return true;
    }

    public function rollback()
    {
        $this->arrMsg[] = __FUNCTION__;
        return true;
    }
}
