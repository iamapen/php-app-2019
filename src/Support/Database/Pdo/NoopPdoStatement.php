<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class NoopPdoStatement implements PdoStatementInterface, \Iterator
{
    /**
     * @inheritDoc
     */
    public function bindValue($parameter, $value, $data_type = null)
    {
        return true;
    }

    public function errorCode()
    {
        return null;
    }

    public function errorInfo()
    {
        return [];
    }

    public function execute($input_parameters = null)
    {
        return true;
    }

    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null)
    {
        return false;
    }

    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null)
    {
        return [];
    }

    public function fetchColumn($column_number = 0)
    {
        return false;
    }

    public function rowCount()
    {
        return 0;
    }

    public function setFetchMode($mode, $params = null)
    {
        return true;
    }

    // region \Iterator

    /**
     * @inheritDoc
     */
    public function current()
    {
        return 'current';
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        // do nothing
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        // do nothing
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        // do nothing
    }

    // endregion
}
