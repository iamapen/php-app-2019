<?php declare(strict_types=1);

namespace Acme\Support\Database\QueryBuilder;

use Acme\Support\Database\Pdo\PdoInterface;

class MysqlBulkInsert
{
    /**
     * Bulk Insert
     * @param $tableName 'INSERT INTO tablename(col, col...)'
     * @param array $chunk 複数行。カラム順序は揃えてあること。
     * @param PdoInterface $pdo
     * @return string SQL
     */
    public function buildStatement(string $tableName, array $chunk, PdoInterface $pdo): string
    {
        if (empty($chunk) || !is_array($chunk[0])) {
            throw new \InvalidArgumentException();
        }

        $sql = 'INSERT INTO ' . $tableName;
        $sql .= '(' . implode(',', array_keys($chunk[0])) . ')';
        $sql .= ' VALUES';
        foreach ($chunk as $row) {
            $sql .= '(';
            foreach ($row as $i => $val) {
                $sql .= $pdo->quote($val) . ',';
            }
            $sql = substr($sql, 0, -1);
            $sql .= '),';
        }
        $sql = substr($sql, 0, -1);
        return $sql;
    }
}
