<?php declare(strict_types=1);

namespace Acme\Support\Utility;

class ArrayUtil
{

    public static function select(array $array, array $columns = []): array
    {
        if (empty($columns)) {
            return [];
        }

        $result = [];
        foreach ($columns as $column) {
            if (array_key_exists($column, $array)) {
                $result[$column] = $array[$column];
            }
        }
        return $result;
    }

    /**
     * 二次元表の射影を返す。SQLでいうSELECT。
     * @param array $table
     * @param string[] $columns 射影するカラム名
     * @return array
     */
    public static function selectTable(array $table, array $columns = []): array
    {
        if (empty($columns)) {
            return $table;
        }

        foreach ($table as $i => $row) {
            $table[$i] = static::select($row, $columns);
        }
        return $table;
    }

    /**
     * 二次元表の末尾に二次元表を追加する
     * @param array $srcTable
     * @param array $subjectTable
     * @return array
     */
    public static function addTable(array $srcTable, array $subjectTable)
    {
        foreach ($subjectTable as $row) {
            $srcTable[] = $row;
        }
        return $srcTable;
    }

    public static function addTableBang(array &$srcTable, array $subjectTable)
    {
        foreach ($subjectTable as $row) {
            $srcTable[] = $row;
        }
    }
}
