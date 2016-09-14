<?PHP

namespace Core;

use \PDO;

class Database {
	private $dsn;
	private $user;
	private $password;

	private $pdo;

	public function __construct($dsn, $user = "root", $password = "root") {
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}

	private function getPDO() {
		if (!isset($this->pdo))
		{
				$this->pdo = new PDO($this->dsn, $this->user, $this->password);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $this->pdo;
	}

	public function query($sqlQuery, $fetchClass = null, $one = false) {
		$query = $this->getPDO()->query($sqlQuery);
		if (strstr($sqlQuery, "SELECT"))
			return $this->fetch($query, $fetchClass, $one);
	}

	public function queryWithParameters($sqlQuery, $params = [], $fetchClass = null, $one = false) {
		$query = $this->getPDO()->prepare($sqlQuery);
		$query->execute($params);
		if (strstr($sqlQuery, "SELECT"))
			return $this->fetch($query, $fetchClass, $one);
	}

	private function fetch($query, $fetchClass, $one) {
		if (is_null($fetchClass))
			$query->setFetchMode(PDO::FETCH_OBJ);
		else
			$query->setFetchMode(PDO::FETCH_CLASS, $fetchClass);
		return $one ? $query->fetch() : $query->fetchAll();
	}
}
