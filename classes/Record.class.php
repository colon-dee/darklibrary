<?php
abstract class Record {

	/**
	 * Array filled with keys representing the fields in the table.
	 *
	 * This filling process happens on object creation (__construct).
	 */
	protected $_data;
	/**
	 * This array caches the values returned by the __get method.
	 */
	protected $_cache;
	/**
	 * Array containing the table fields, it stores the query made
	 * by the method tableField().
	 */
	private static $tableFieldsCache = array();

	function __construct($id = null) {
		/* HACK: Apesar de esse código ficar no construtor (que teoricamente é
		 * executado apenas uma vez por objeto), é preciso fazer esta
		 * verificação para que o método fetchObject da classe PDOStatement
		 * funcione corretamente.
		 */
		if (!isset($this->_data)) {
			$this->_data = array();

			/* caso o array _data não tenha sido preenchido com nenhum valor,
			 * realiza uma consulta do tipo DESCRIBE ao banco de dados para
			 * inicializar este array com os devidos campos da tabela.
			 */
			foreach (self::tableFields() as $tableField) {
				$this->_data[$tableField] = null;
			}
		}

		$this->_cache = array();

		if ($id) {
			// se não for um array, transforma em um
			if (!is_array($id)) {
				$id = array($id);
			}

			$fields = $this->load($id);
			if ($fields) {
				$this->_data = $fields;
			}
		}
	}

	/**
	 * Função mágica [1] que retorna os atributos do objeto.
	 *
	 * Esta função segue a seguinte sequência:
	 *  1. Verifica se o atributo existe no array $_cache
	 *  2. Quando $objeto->atributo é acessado, verifica se existe uma função
	 *     chamada getAtributo na classe
	 *  3. Verifica se o atributo existe no array $_data (carregado do banco
	 *     de dados)
	 *  4. Caso nenhum dos três itens anteriores seja verdadeiro, não retorna
	 *     coisa alguma e exibe uma mensagem de erro no log
	 *
	 * [1] http://php.net/manual/en/language.oop5.magic.php
	 *
	 * @return mixed o atributo com o respectivo nome ou nada em caso de erro
	 */
	function __get($prop) {
		if (array_key_exists($prop, $this->_cache)) {
			return $this->_cache[$prop];
		} else if (method_exists($this, 'get' . ucfirst($prop))) {
			$this->_cache[$prop] = call_user_func(array($this, 'get' . ucfirst($prop)));
			return $this->_cache[$prop];
		} else if (array_key_exists($prop, $this->_data)) {
			return $this->_data[$prop];
		} else {
			$trace = debug_backtrace();
			$linha = $trace[0]['file'] . ':' . $trace[0]['line'];
			error_log('Trying to access an undefined property: ' . get_class($this) . '->' . $prop . ' on ' . $linha);
		}
	}

	function __set($prop, $value) {
		if (method_exists($this, 'set' . ucfirst($prop))) {
			return call_user_func(array($this, 'set' . ucfirst($prop)), $value);
		} else {
			$this->_data[$prop] = $value;
		}
	}

	function __isset($prop) {
		return array_key_exists($prop, $this->_data) || method_exists($this, 'get' . ucfirst($prop));
	}

	function __clone() {
		foreach ($this->getPK() as $pk) {
			unset($pk);
		}
	}

	function store() {
		$pks = $this->getPK();
		$newRecord = false;
		$keys = array();

		// se alguma das chaves primárias estiver vazia é um novo registro
		foreach ($pks as $i => $pk) {
			if (empty($this->_data[$pk])) {
				$newRecord = true;
			} else {
				$keys[$i] = $this->_data[$pk];
			}
		}
		// se não conseguir carregar do banco, é um novo registro
		if (!$this->load($keys)) {
			$newRecord = true;
		}

		// se for novo registro, faz INSERT, senão, faz UPDATE
		if ($newRecord) {
			$sql = new SqlInsert();
			$sql->setEntity($this->getEntity());

			// adiciona os campos
			foreach ($this->_data as $column => $value) {
				$sql->setRowData($column, $value);
			}
		} else {
			// Remove htmlspecialchars dos campos que não foram modificados para que o mesmo não seja aplicado duas vezes.
			foreach ($this->load($keys) as $column => $previousKey) {
					$this->{$column} = $this->{$column} != $previousKey ? $this->{$column} : htmlspecialchars_decode($previousKey);
			}

			$sql = new SqlUpdate();
			$sql->setEntity($this->getEntity());

			// adiciona o critério a partir das chaves primárias
			$criteria = new Criteria();
			foreach ($pks as $i => $pk) {
				$criteria->add(new Filter($pk, '=', $keys[$i]));
			}
			$sql->setCriteria($criteria);

			// adiciona os campos na consulta
			foreach ($this->_data as $column => $value) {
				// ignora as chaves primárias
				if (in_array($column, $pks)) {
					continue;
				}

				$sql->setRowData($column, $value);
			}
		}

		$con = static::getConnection();
		$result = $con->exec($sql->getInstruction());

		if ($newRecord && count($pks) == 1) {
			$pk = $pks[0];
			$this->_data[$pk] = $con->lastInsertId();
		}

		return $result;
	}

	function delete() {
		$con = static::getConnection();
		$criteria = new Criteria();

		foreach ($this->getPK() as $pk) {
			$criteria->add(new Filter($pk, '=', $this->_data[$pk]));
		}

		$sql = new SqlDelete();
		$sql->setEntity($this->getEntity());
		$sql->setCriteria($criteria);

		return $con->exec($sql->getInstruction());
	}

	private function getEntity() {
		$clazz = get_class($this);
		return constant("$clazz::TABLE");
	}

	private function getPK() {
		$clazz = get_class($this);
		return explode(',', constant("$clazz::PK"));
	}

	private function load($id) {
		if (empty($id)) {
			return null;
		}

		$sql = new SqlSelect();
		$sql->setEntity($this->getEntity());
		$sql->addColumn('*');

		$criteria = new Criteria();
		foreach ($this->getPK() as $i => $pk) {
			$criteria->add(new Filter($pk, '=', $id[$i]));
		}

		$sql->setCriteria($criteria);

		$con = static::getConnection();
		$result = $con->query($sql->getInstruction());

		if ($result) {
			return $result->fetch(PDO::FETCH_ASSOC);
		}
	}

	/**
	 * Realiza uma consulta do tipo DESCRIBE no banco de dados.
	 *
	 * @return array com os campos da respectiva tabela
	 */
	private static function tableFields() {
		$table = static::TABLE;

		if (!array_key_exists($table, self::$tableFieldsCache)) {
			$sql = "DESCRIBE $table";

			$con = static::getConnection();
			$result = $con->query($sql);

			self::$tableFieldsCache[$table] = $result->fetchAll(PDO::FETCH_COLUMN);
		}

		return self::$tableFieldsCache[$table];
	}

	private static function prepareSQL($conditions = null, $options = array()) {
		$table = static::TABLE;

		// SELECT
		$select = isset($options['select']) ? $options['select'] : '*';

		$sql = "SELECT $select FROM $table";

		// JOINS
		if (isset($options['joins'])) {
			$sql .= ' ' . $options['joins'];
		}

		// CONDITIONS
		if (!empty($conditions)) {
			/* se for um array, será algo como:
			 * array('nome LIKE ? and YEAR(dataNascimento) = ?', 'fulano', 1980)
			 */
			if (is_array($conditions)) {
				// troca o ? pelos valores corretos, já escapados
				$sqlCustom = new SqlCustom($conditions[0]);

				for ($i = 1; $i < count($conditions); $i++) {
					$sqlCustom->add($conditions[$i]);
				}

				$where = $sqlCustom->getInstruction();
			} else {
				$where = $conditions;
			}

			$sql .= " WHERE $where";
		}

		// GROUP
		$sql .= isset($options['group']) ? " GROUP BY " . $options['group'] : '';

		// ORDER
		$sql .= isset($options['order']) ? " ORDER BY " . $options['order'] : '';

		// LIMIT
		$sql .= isset($options['limit']) ? " LIMIT " . $options['limit'] : '';

		// OFFSET
		$sql .= isset($options['offset']) ? " OFFSET " . $options['offset'] : '';

		return $sql;
	}

	static function find($conditions = null, $options = array()) {
		// cria o SQL
		$sql = self::prepareSQL($conditions, $options);

		$con = static::getConnection();
		$result = $con->query($sql);

		if (!$result) {
			return array();
		}

		$results = array();

		while ($row = $result->fetchObject(get_called_class())) {
			$results[] = $row;
		}

		return $results;
	}

	static function count($conditions = null, $options = array()) {
		if (isset($options['select']) && !empty($options['select'])) {
			throw new InvalidArgumentException('Não faz sentido definir os campos a serem buscados em uma consulta COUNT');
		}

		if (isset($options['order']) && !empty($options['order'])) {
			throw new InvalidArgumentException('Não faz sentido definir ordenação em uma consulta COUNT');
		}

		if (isset($options['limit']) && !empty($options['limit'])) {
			throw new InvalidArgumentException('Não faz sentido definir limite de registros em uma consulta COUNT');
		}

		if (isset($options['offset']) && !empty($options['offset'])) {
			throw new InvalidArgumentException('Não faz sentido definir deslocamento de registros em uma consulta COUNT');
		}

		// indica o que deve ser buscado na consulta
		$options['select'] = 'COUNT(*)';

		// cria o SQL
		$sql = self::prepareSQL($conditions, $options);

		$con = static::getConnection();
		$result = $con->query($sql);

		if ($result) {
			return $result->fetchColumn(0);
		}

		return 0;
	}

	public static function getConnection() {
		return ConnectionFactory::getConnection();
	}

}
?>
