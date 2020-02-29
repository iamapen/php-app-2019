<?php

namespace Acme\Test\TestCase;

abstract class DbBaseTestCase extends \PHPUnit\DbUnit\TestCase
{
    use DbConnectable;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // region DbUnit
    protected function getConnection()
    {
        return new \PHPUnit\DbUnit\Database\DefaultConnection($this->getPdo());
    }

    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\ArrayDataSet([]);
    }

    protected function getSetUpOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::CLEAN_INSERT();
    }

    protected function getTearDownOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::NONE();
    }
    // endregion
}
