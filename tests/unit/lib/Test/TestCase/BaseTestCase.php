<?php

namespace Acme\Test\TestCase;

abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
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
}
