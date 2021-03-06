<?php

/**
 * OneDB Database Framework
 *
 * @author cvgellhorn
 */
class OneDB
{
  /**
   * Instance implementation
   *
   * @var OneDB
   */
  protected static $_instance = null;

  /**
   * Collection of active database connections
   *
   * @var array of OneDB connections
   */
  protected static $_connections = [];

  /**
   * PDO object
   *
   * @var PDO
   */
  protected $_pdo;

  /**
   * The driver level statement PDO
   *
   * @var PDOStatement
   */
  protected $_stmt;

  /**
   * Debug mode state
   *
   * @var bool
   */
  protected $_debugMode = false;

  /**
   * Default DB configuration
   *
   * @var array
   */
  protected $_config = [
    'pdo_type'  => 'mysql',
    'host'      => 'localhost',
    'charset'   => 'utf8',
    'database'  => null,
    'user'      => null,
    'password'  => null
  ];

  /**
   * Debug style
   *
   * @var array
   */
  public $debugStyle = [
    'border: 2px solid #d35400',
    'border-radius: 3px',
    'background-color: #e67e22',
    'margin: 5px 0 5px 0',
    'color: #ffffff',
    'padding: 5px'
  ];

  /**
   * Single pattern implementation
   *
   * @param array $config Connection configs
   * @return OneDB
   */
  public static function load($config = [])
  {
    if (null === self::$_instance) {
      self::$_instance = self::_create($config);
    }
    return self::$_instance;
  }

  /**
   * Handle database connections
   *
   * @param string $name Connection name
   * @param array $config Connection configs
   * @return OneDB
   */
  public static function getConnection($name = null, $config = [])
  {
    if (null === $name) {
      return self::_create($config);
    } else {
      if (!isset(self::$_connections[$name])) {
        self::$_connections[$name] = self::_create($config);
      }
      return self::$_connections[$name];
    }
  }

  /**
   * Create new OneDB connection
   *
   * @param array $config Connection configs
   * @return OneDB
   * @throws OneException
   */
  protected static function _create($config)
  {
    if (!empty($config)) {
      return new self($config);
    } else {
      throw new OneException('OneDB configuration is not set');
    }
  }

  /**
   * Create DB object and connect to database
   *
   * @throws OneException
   */
  protected function __construct($config)
  {
    try {
      if (!extension_loaded('pdo_mysql')) {
        throw new OneException('pdo_mysql extension is not installed');
      }

      // Prepare database configuration
      $config = $this->_prepareConfig($config);

      $this->_pdo = new PDO(
        $config['pdo_type'] . ':' . $config['dsn'],
        $config['user'],
        $config['password']
      );

      // Always use exceptions
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Set character encoding
      $this->_pdo->exec('SET CHARACTER SET ' . $config['charset']);
    } catch (PDOException $e) {
      throw new OneException($e->getMessage());
    }
  }

  /**
   * Prepare database configuration
   *
   * @param array $config Connection configs
   * @return array Matched connection configs
   * @throws OneException
   */
  protected function _prepareConfig($config)
  {
    $config = array_merge($this->_config, $config);
    foreach ($config as $key => $val) {
      if (null === $val) {
        throw new OneException('Could not connect to database, missing parameter: ' . $key);
      }
    }

    $dsn = [
      'host='     . $config['host'],
      'dbname='   . $config['database'],
      'charset='  . $config['charset']
    ];

    if (isset($config['port'])) {
      $dsn[] = 'port=' . $config['port'];
    }

    $config['dsn'] = implode(';', $dsn);
    return $config;
  }

  /**
   * Prepare SQL statement for executing
   *
   * @param string $sql SQL statement
   * @return OneDB
   */
  protected function _prepare($sql)
  {
    $this->_stmt = $this->_pdo->prepare($sql);
    return $this;
  }

  /**
   * Build where clause
   *
   * @param array $where Where conditions
   * @param string $query Query string
   */
  protected function _buildWhere(&$where, &$query)
  {
    if (!empty($where)) {
      $expr = [];
      foreach ($where as $key => $val) {
        if ($val instanceof OneExpr) {
          $expr[] = str_replace('?', $val, $key);
          unset($where[$key]);
        }
      }
      $query .= ' WHERE ' . implode(' AND ', array_keys($where))
        . ((!empty($expr)) ? ' AND ' . implode(' AND ', $expr) : '');
    }
  }

  /**
   * Bind SQL query params to PDO statement object
   *
   * @param array $data SQL query params
   * @return OneDB
   */
  protected function _bindParams($data)
  {
    if ($this->_debugMode) $this->dump(print_r($data, true));

    $count = count($data);
    for ($i = 0; $i < $count; $i++) {
      $this->_stmt->bindParam($i + 1, $data[$i]);
    }

    return $this;
  }

  /**
   * Execute SQL statement
   *
   * @return PDOStatement
   * @throws OneException
   */
  protected function _execute()
  {
    try {
      if ($this->_debugMode) $this->dump($this->_stmt->queryString);
      $this->_stmt->execute();
    } catch (PDOException $e) {
      throw new OneException('PDO Mysql execution error: ' . $e->getMessage());
    }

    return $this->_stmt;
  }

  /**
   * Get the current PDO object
   *
   * @return PDO
   */
  public function getPDO()
  {
    return $this->_pdo;
  }

  /**
   * Return given value with quotes
   *
   * @param string $val Value
   * @return string Value with quotes
   */
  public function quote($val)
  {
    return "'$val'";
  }

  /**
   * Return given value with backticks
   *
   * @param string $val Value
   * @return string Value with backticks
   */
  public function btick($val)
  {
    return "`$val`";
  }

  /**
   * Set debug mode
   *
   * @param bool $status Debug mode status
   * @return $this
   */
  public function debug($status = true)
  {
    $this->_debugMode = $status;
    return $this;
  }

  /**
   * Debug dump
   *
   * @param mixed $val Value to dump
   */
  public function dump($val)
  {
    echo '<div style="' . implode(';', $this->debugStyle) . '"><pre>' . $val . '</pre></div>';
  }

  /**
   * Initiates a transaction
   *
   * @return bool TRUE on success or FALSE on failure
   */
  public function beginTransaction()
  {
    return $this->_pdo->beginTransaction();
  }

  /**
   * Commits a transaction
   *
   * @return bool TRUE on success or FALSE on failure
   */
  public function commit()
  {
    return $this->_pdo->commit();
  }

  /**
   * Rolls back a transaction
   *
   * @return bool TRUE on success or FALSE on failure
   */
  public function rollBack()
  {
    return $this->_pdo->rollBack();
  }

  /**
   * Checks if inside a transaction
   *
   * @return bool TRUE on success or FALSE on failure
   */
  public function inTransaction()
  {
    return $this->_pdo->inTransaction();
  }

  /**
   * Get last insert ID
   *
   * @return int Last insert ID
   */
  public function lastInsertId()
  {
    return (int) $this->_pdo->lastInsertId();
  }

  /**
   * Fetch all data by SQL statement
   *
   * @param string $sql SQL statement
   * @return array SQL result
   */
  public function fetchAll($sql)
  {
    return $this->_prepare($sql)->_execute()->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Fetch all data by SQL statement and merge by field
   *
   * @param string $sql SQL statement
   * @param string $key Optional | array key
   * @return array SQL result
   */
  public function fetchAssoc($sql, $key = null)
  {
    // Raw result data
    $data = $this->_prepare($sql)->_execute()->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    if (!empty($data)) {
      $key = ($key && isset($data[0][$key])) ? $key : key($data[0]);
      foreach ($data as $d) {
        $result[$d[$key]] = $d;
      }
    }

    return $result;
  }

  /**
   * Fetch row by SQL statement
   *
   * @param string $sql SQL statement
   * @return array SQL result
   */
  public function fetchRow($sql)
  {
    return $this->_prepare($sql)->_execute()->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Fetch single value by SQL statement
   *
   * @param string $sql SQL statement
   * @return mixed Result value
   */
  public function fetchOne($sql)
  {
    $result = $this->_prepare($sql)->_execute()->fetch(PDO::FETCH_NUM);
    return isset($result[0]) ? $result[0] : null;
  }

  /**
   * Executes an SQL statement
   *
   * @param string $sql SQL statement
   * @return array|bool|mixed|null SQL result
   * @throws OneException
   */
  public function query($sql)
  {
    try {
      /*** @var $result PDOStatement */
      $result = $this->_pdo->query($sql);
    } catch (PDOException $e) {
      throw new OneException('PDO Mysql statement error: ' . $e->getMessage());
    }

    $columnCount = $result->columnCount();
    $rowCount = $result->rowCount();

    // If statment is as SELECT statement
    if ($columnCount > 0) {
      // Equal to fetchOne
      if ($columnCount === 1 && $rowCount === 1) {
        $res = $result->fetch(PDO::FETCH_NUM);
        return isset($res[0]) ? $res[0] : null;

        // Equal to fetchRow
      } else if ($columnCount > 1 && $rowCount === 1) {
        return $result->fetch(PDO::FETCH_ASSOC);

        // Equal to fetchAll
      } else {
        return $result->fetchAll(PDO::FETCH_ASSOC);
      }
    } else {
      // No result
      return true;
    }
  }

  /**
   * Insert given data into database
   *
   * @param string $table DB table name
   * @param array $data Data to insert
   * @return int Last insert ID
   */
  public function insert($table, $data)
  {
    $keys = [];
    $values = [];
    foreach ($data as $key => $val) {
      $keys[] = $this->btick($key);
      if ($val instanceof OneExpr) {
        $values[] = $val;
        unset($data[$key]);
      } else {
        $values[] = '?';
      }
    }

    $query = 'INSERT INTO ' . $this->btick($table)
      . ' (' . implode(', ', $keys) . ')'
      . ' VALUES (' . implode(', ', $values) . ')';

    $this->_prepare($query)->_bindParams(array_values($data))->_execute();
    return $this->lastInsertId();
  }

  /**
   * Do a multi insert
   *
   * @param string $table DB table name
   * @param array $keys DB table columns
   * @param array $data Data to insert
   */
  public function multiInsert($table, $keys, $data)
  {
    foreach ($keys as &$key) {
      $key = $this->btick($key);
    }

    $values = [];
    foreach ($data as $vals) {
      foreach ($vals as &$val) {
        $val = $this->quote($val);
      }
      $values[] = '(' . implode(',', $vals) . ')';
    }

    $query = 'INSERT INTO ' . $this->btick($table)
      . ' (' . implode(',', $keys) . ')'
      . ' VALUES ' . implode(',', $values);

    $this->_prepare($query)->_execute();
  }

  /**
   * Update data if exist, otherwise insert new data
   *
   * @param string $table DB table name
   * @param array $data Data to insert or update
   * @return int ID of the last inserted or updated row
   */
  public function save($table, $data)
  {
    $keys = [];
    $values = [];
    $updateVals = [];

    foreach ($data as $key => $val) {
      $keys[] = $field = $this->btick($key);
      if ($val instanceof OneExpr) {
        $values[] = $val;
        $updateVals[] = $field . '=' . $val;
        unset($data[$key]);
      } else {
        $values[] = '?';
        $updateVals[] = $field . '=?';
      }
    }

    $query = 'INSERT INTO ' . $this->btick($table)
      . ' (' . implode(', ', $keys) . ')'
      . ' VALUES (' . implode(', ', $values) . ')'
      . ' ON DUPLICATE KEY UPDATE ' . implode(',', $updateVals);

    $vals = array_values($data);
    $this->_prepare($query)->_bindParams(array_merge($vals, $vals))->_execute();

    return $this->lastInsertId();
  }

  /**
   * Update data by given condition
   *
   * @param string $table DB table name
   * @param array $data Data to update
   * @param array $where Update condition
   */
  public function update($table, $data, $where = [])
  {
    $values = [];
    foreach ($data as $key => $val) {
      if ($val instanceof OneExpr) {
        $values[] = $this->btick($key) . ' = ' . $val;
        unset($data[$key]);
      } else {
        $values[] = $this->btick($key) . ' = ?';
      }
    }

    $query = 'UPDATE ' . $this->btick($table) . ' SET ' . implode(', ', $values);
    $this->_buildWhere($where, $query);

    $params = array_merge(
      array_values($data),
      array_values($where)
    );

    $this->_prepare($query)->_bindParams($params)->_execute();
  }

  /**
   * Delete from database table
   *
   * @param string $table DB table name
   * @param array $where Delete condition
   */
  public function delete($table, $where = [])
  {
    $query = 'DELETE FROM ' . $this->btick($table);
    $this->_buildWhere($where, $query);

    $this->_prepare($query);
    if (!empty($where)) {
      $this->_bindParams(array_values($where));
    }

    $this->_execute();
  }

  /**
   * Truncate database table
   *
   * @param string $table DB table name
   */
  public function truncate($table)
  {
    $this->_prepare('TRUNCATE TABLE ' . $this->btick($table))->_execute();
  }

  /**
   * Drop database table
   *
   * @param string $table DB table name
   */
  public function drop($table)
  {
    $this->_prepare('DROP TABLE ' . $this->btick($table))->_execute();
  }

  /**
   * Describe database table
   *
   * @param string $table DB table name
   * @return array Table structure
   */
  public function describe($table)
  {
    return $this->fetchAssoc('DESCRIBE ' . $this->btick($table));
  }
}


/**
 * OneDB database expression
 */
class OneExpr
{
  /**
   * @var string Database expression
   */
  public $expr;

  /**
   * Expression constructor
   *
   * @param string $expr Database expression
   */
  public function __construct($expr)
  {
    $this->expr = $expr;
  }

  /**
   * Magic to string method
   *
   * @return string Database expression
   */
  public function __toString()
  {
    return $this->expr;
  }
}


/**
 * OneDB Exception class
 */
class OneException extends Exception
{}
