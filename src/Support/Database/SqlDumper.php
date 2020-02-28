<?php declare(strict_types=1);

namespace Acme\Support\Database;

/**
 * 生でPDOを使うプロジェクト用のSQL dumper
 * @author iamapen
 */
class SqlDumper
{
    /**
     * 実行するPrepared Statementを non-prepared なSQLにして返す
     * @param string $psql (non) prepared statement sql
     * @param array $params placeholders
     * @return string binded sql
     */
    static public function dump(string $psql, ?array $params = []): string
    {
        $keys = [];
        $values = [];

        $isNamedHolders = false;
        if (is_array($params) && !empty($params) && is_string(key($params))) {
            uksort($params, function ($k1, $k2) {
                return strlen($k2) - strlen($k1);
            });
            $isNamedHolders = true;
        }

        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . ltrim($key, ':') . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[] = "'" . addslashes($value) . "'";
            } elseif (is_int($value)) {
                $values[] = strval($value);
            } elseif (is_float($value)) {
                $values[] = strval($value);
            } elseif (is_array($value)) {
                //$values[] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[] = 'NULL';
            }
        }
        if ($isNamedHolders) {
            return preg_replace($keys, $values, $psql);
        } else {
            return preg_replace($keys, $values, $psql, 1, $count);
        }
    }
}
