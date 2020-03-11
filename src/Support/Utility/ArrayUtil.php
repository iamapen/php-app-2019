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
     *
     * 追加件数は10万程度が限界か。
     * メソッドにせず利用側で書いた方が圧倒的に早い
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

    // 速度を求めるならメソッドにしない方がいい
    //public static function addTableBang(array &$srcTable, array $subjectTable)
    //{
    //    foreach ($subjectTable as $row) {
    //        $srcTable[] = $row;
    //    }
    //}

    /**
     * チャンク集合に追加する
     *
     * 追加件数は100万くらいが限界か
     * @param int $chunkSize
     * @param array $srcChunks チャンク集合。数値キーである必要がある
     * @param array $subjectTable 追加する二次元表
     * @return array
     */
    public static function chunkingAdd(int $chunkSize, array $srcChunks, array $subjectTable): array
    {
        if (0 < $lastChunkIndex = count($srcChunks)) {
            $lastChunkIndex = $lastChunkIndex - 1;
        }

        $i = 0;
        if (!empty($srcChunks)) {
            $i = count($srcChunks[$lastChunkIndex]);
        }
        for (; $i < $chunkSize; $i++) {
            if (empty($subjectTable)) {
                return $srcChunks;
            }
            $srcChunks[$lastChunkIndex][] = array_shift($subjectTable);
        }

        $chunks = array_chunk($subjectTable, $chunkSize);
        unset($subjectTable);
        foreach ($chunks as $i => $chunk) {
            //$srcChunks = array_merge($srcChunks, [$chunk]);
            $srcChunks[] = $chunk;
        }
        return $srcChunks;
    }
}
