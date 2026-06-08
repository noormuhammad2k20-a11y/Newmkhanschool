<?php
namespace App\Core;

use PDO;

abstract class Model {
    protected static $table = null;
    protected $attributes = [];

    public function __construct(array $attributes = []) {
        $this->attributes = $attributes;
    }

    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function __isset($key) {
        return isset($this->attributes[$key]);
    }

    public static function getTable(): string {
        if (static::$table === null) {
            $parts = explode('\\', static::class);
            $className = end($parts);
            // Basic pluralization logic
            $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
            if (str_ends_with($tableName, 'y')) {
                return substr($tableName, 0, -1) . 'ies';
            }
            return $tableName . 's';
        }
        return static::$table;
    }

    /**
     * Find a single record by ID.
     */
    public static function find($id) {
        $db = Database::getConnection();
        $table = static::getTable();
        $stmt = $db->prepare("SELECT * FROM `{$table}` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new static($row) : null;
    }

    /**
     * Retrieve all records with lazy-loading chunk/limit configuration.
     */
    public static function all(int $limit = 100, int $offset = 0): array {
        $db = Database::getConnection();
        $table = static::getTable();
        $stmt = $db->prepare("SELECT * FROM `{$table}` LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new static($row), $rows);
    }

    /**
     * Retrieve records matching specific criteria.
     */
    public static function where(string $column, $value, int $limit = 100): array {
        $db = Database::getConnection();
        $table = static::getTable();
        $stmt = $db->prepare("SELECT * FROM `{$table}` WHERE `{$column}` = ? LIMIT ?");
        $stmt->bindValue(1, $value);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new static($row), $rows);
    }

    /**
     * Save the model instance (insert or update).
     */
    public function save(): bool {
        $db = Database::getConnection();
        $table = static::getTable();

        if (isset($this->attributes['id'])) {
            // Update
            $id = $this->attributes['id'];
            $fields = $this->attributes;
            unset($fields['id']);

            $setClause = [];
            $values = [];
            foreach ($fields as $key => $val) {
                $setClause[] = "`{$key}` = ?";
                $values[] = $val;
            }
            $values[] = $id;

            $sql = "UPDATE `{$table}` SET " . implode(', ', $setClause) . " WHERE `id` = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute($values);
        } else {
            // Insert
            $fields = $this->attributes;
            $columns = array_keys($fields);
            $placeholders = array_fill(0, count($columns), '?');

            $sql = "INSERT INTO `{$table}` (" . 
                implode(', ', array_map(fn($col) => "`{$col}`", $columns)) . 
                ") VALUES (" . implode(', ', $placeholders) . ")";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute(array_values($fields));
            if ($result) {
                $this->attributes['id'] = $db->lastInsertId();
            }
            return $result;
        }
    }

    /**
     * Delete the model instance from database.
     */
    public function delete(): bool {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        $db = Database::getConnection();
        $table = static::getTable();
        $stmt = $db->prepare("DELETE FROM `{$table}` WHERE `id` = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    /**
     * Convert model properties to associative array.
     */
    public function toArray(): array {
        return $this->attributes;
    }
}
