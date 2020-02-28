<?php declare(strict_types=1);

namespace Acme\Test\Fake\Support\Database\Pdo;

use Acme\Support\Database\Pdo\PdoStaetment;

class FakePdoStatement extends PdoStaetment
{
    /** @var string|null */
    public $str;

    public function __construct() { }

    public function errorCode() {
        return $this->str;
    }
}
