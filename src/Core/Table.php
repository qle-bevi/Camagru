<?php

namespace Core;

abstract class Table
{
    private $db;
    private $entryClass;

    protected $table;

	protected $fillables = [];

    public function __construct(Database $db)
    {
        $this->db = $db;
        $class = get_called_class();
        if (!isset($this->table)) {
            $chunks = explode("\\", $class);
            $this->table = strtolower(str_replace("Table", "", end($chunks)))."s";
        }
        $path = ROOT."src/".str_replace(["Table", "\\"], ["Entry", "/"], $class).".php";
        $this->entryClass = file_exists($path) ? str_replace("Table", "Entry", $class) : null;
    }

    public function tableName()
    {
        return $this->table;
    }

    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}");
    }

    public function byId($id)
    {
        return $this->db->queryWithParameters("SELECT * FROM {$this->table} WHERE id=?", [$id]);
    }

    public function insert($params)
    {
		foreach ($params as $k => $v) {
			if (!in_array($k, $this->fillables))
				unset($params[$k]);
		}
        $fields = implode(", ", array_keys($params));
        $values = implode("', '", array_values($params));
        $values = "'{$values}'";
        return $this->db->query("
		INSERT INTO {$this->table} ({$fields}) VALUES ({$values})
		");
    }

	public function update($params, $where) {
		$sql = "UPDATE {$this->table} ";
		foreach ($params as $k => $v) {
			if (in_array($k, $this->fillables))
				$sql .= "SET {$k}='{$v}', ";
		}
		$sql = rtrim($sql, ", ");
		$sql .= " WHERE ";
		foreach ($where as	$k => $v) {
			$sql .= "{$k}='{$v}' AND ";
		}
		$sql = rtrim($sql, " AND ");
		return $this->db->query($sql);
	}

    public function query($query, $parameters = null, $one = false)
    {
        if (isset($parameters)) {
            return $this->db->queryWithParameters($query, $parameters, $this->entryClass, $one);
        } else {
            return $this->db->query($query, $this->entryClass, $one);
        }
    }
}
