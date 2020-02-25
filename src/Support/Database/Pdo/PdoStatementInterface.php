<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

interface PdoStatementInterface extends \Traversable
{
    /**
     * @param string $parameter
     * @param mixed $value
     * @param int|null $data_type
     * @return bool
     */
    public function bindValue($parameter, $value, $data_type = null);

    /**
     * @return string
     */
    public function errorCode();

    /**
     * @return array
     */
    public function errorInfo();

    /**
     * @param array|null $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null);

    /**
     * @param int|null $fetch_style
     * @param int|null $cursor_orientation
     * @param int|null $cursor_offset
     * @return mixed
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null);

    /**
     * @param int|null $fetch_style
     * @param int|string|null $fetch_argument
     * @param array|null $ctor_args
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null);

    /**
     * @param int $column_number
     * @return string
     */
    public function fetchColumn($column_number = 0);

    /*
     * @return int
     */
    public function rowCount();

    public function setFetchMode($mode, $params = null);
}
