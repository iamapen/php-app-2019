<?php

namespace Acme\Support\Utility;

use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{

    function test_select()
    {
        $arr = ['firstName' => 'arare', 'lastName' => 'norimaki', 'gender' => 'f'];

        $this->assertSame([], ArrayUtil::select($arr, []));
        $this->assertSame(['lastName' => 'norimaki'], ArrayUtil::select($arr, ['lastName']));
        $this->assertSame(['lastName' => 'norimaki', 'gender' => 'f'],
            ArrayUtil::select($arr, ['lastName', 'gender'])
        );
    }

    function test_selectTable()
    {
        $table = [
            ['id' => '01', 'lastName' => 'norimaki', 'firstName' => 'arare', 'gender' => 'female', 'age' => '18'],
            ['id' => '02', 'lastName' => 'soramame', 'firstName' => 'taro', 'gender' => 'male', 'age' => '20'],
            ['id' => '03', 'lastName' => 'kimidori', 'firstName' => 'akane', 'gender' => 'female', 'age' => '18'],
        ];

        // 射影なしは入力値と同じ
        $this->assertSame($table, ArrayUtil::selectTable($table));

        // 一般的な利用
        $ex = [
            ['id' => '01', 'firstName' => 'arare'],
            ['id' => '02', 'firstName' => 'taro'],
            ['id' => '03', 'firstName' => 'akane'],
        ];
        $this->assertSame($ex, ArrayUtil::selectTable($table, ['id', 'firstName']));

        // 該当カラムなし
        $ex = [[], [], []];
        $this->assertSame($ex, ArrayUtil::selectTable($table, ['hoge']));

        // 入力が空
        $this->assertSame([], ArrayUtil::selectTable([], ['id', 'firstName']));
    }

    function test_addTable()
    {
        $table1 = [
            ['name' => 'arare', 'gender' => 'f'],
            ['name' => 'taro', 'gender' => 'm'],
        ];
        $table2 = [
            ['name' => 'midri', 'gender' => 'f'],
        ];

        $this->assertSame([], ArrayUtil::addTable([], []));
        $this->assertSame($table1, ArrayUtil::addTable([], $table1));
        $this->assertSame($table1, ArrayUtil::addTable($table1, []));

        $ex = [
            ['name' => 'arare', 'gender' => 'f'],
            ['name' => 'taro', 'gender' => 'm'],
            ['name' => 'midri', 'gender' => 'f'],
        ];
        $this->assertSame($ex, ArrayUtil::addTable($table1, $table2));
    }

    function test_chunkingAdd()
    {
        // 空に追加
        $srcChunk = [];
        $arr2 = [1, 2, 3, 4, 5];
        $ex = [
            [1, 2],
            [3, 4],
            [5],
        ];
        $this->assertSame($ex, ArrayUtil::chunkingAdd(2, $srcChunk, $arr2));

        // 最初から新チャンクになるケース
        $srcChunk = [[1, 2, 3]];
        $arr2 = [4, 5, 6, 7, 8, 9, 10];
        $ex = [
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
            [10],
        ];
        $this->assertSame($ex, ArrayUtil::chunkingAdd(3, $srcChunk, $arr2));

        // 追加が空のケース
        $srcChunk = [[1, 2]];
        $this->assertSame($srcChunk, ArrayUtil::chunkingAdd(3, $srcChunk, []));

        // 両方空のケース
        $this->assertSame([], ArrayUtil::chunkingAdd(3, [], []));
    }
}
