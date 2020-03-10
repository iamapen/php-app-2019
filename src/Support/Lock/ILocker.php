<?php

namespace Acme\Support\Lock;

interface ILocker
{
    public function getLock(): bool;
}
