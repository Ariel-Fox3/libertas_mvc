<?php
  namespace Mubbi;

  use PDO;
  use PDOException;
  use PDOStatement;
  use ReflectionClass;
  use SqlFormatter;

  class Database {

    private $connection = NULL;

    public function arrayToObject(array $array, string $class_name, string $var_name = null){
      $r = new ReflectionClass($class_name);
      $object = $r->newInstanceWithoutConstructor();
      $list = $r->getProperties();
      foreach($list as $prop){
        $prop->setAccessible(true);
        if ($var_name !== null) {
          if (is_array($prop->getValue($object)) && $var_name == $prop->name) {
            $tmp_a = array();
            foreach ($prop->getValue($object) as $key => $value) {
              if(isset($array[$key]))
                $tmp_a[$key] = $array[$key];
            }
            return $tmp_a;
          }
        } else {
          if(isset($array[$prop->name]))
            $prop->setValue($object, $array[$prop->name]);
          
            return $object;
        }
      } 
    }

    public function __construct($dbtype, $hostname, $username, $password, $database, $port, $session = null) {
      try {
        mysqli_report(MYSQLI_REPORT_STRICT);
  
        $this->connection = @new \mysqli($hostname, $username, $password, $database, $port);
      } catch (\mysqli_sql_exception $e) {
        throw new \Exception('Erro: Não foi possível conectar-se ao banco de dados!' . $e->getMessage());
      }

      $this->connection->set_charset("utf8");
		  $this->connection->query("SET SQL_MODE = ''");
    }

    public function formatChange(array $change) {
      $ret = array();
      if (sizeof($change) > 0) {
        foreach ($change as $key => $value) {
          if ($value !== null) {
            $v = $value == 'NULL' || $value == 'null' ? 'NULL' : ('"' . addslashes($value) . '"');
            $ret[] = sprintf('`%s` = %s', $key, $v);
          }
        }
      }

      return implode(', ', $ret);
    }

    public function query($sql) {
      $query = $this->connection->query($sql);
      
      if (!$this->connection->errno) {
        if ($query instanceof \mysqli_result) {
          $data = array();
  
          while ($row = $query->fetch_assoc()) {
            $data[] = $row;
          }
  
          $result = new \stdClass();
          $result->num_rows = $query->num_rows;
          $result->row = isset($data[0]) ? $data[0] : array();
          $result->rows = $data;
  
          $query->close();
  
          return $result;
        } else {
          return true;
        }
      } else {
        throw new \Exception('Erro: ' . $this->connection->error  . '<br>Código: ' . $this->connection->errno . '<br>' . $sql);
      }
    }

    public function escape($value) {
      return $this->connection->real_escape_string($value);
    }
  
    public function countAffected() {
      return $this->connection->affected_rows;
    }
  
    public function getLastId() {
      return $this->connection->insert_id;
    }

    public function isConnected() {
      return $this->connection->ping();
    }

    public function __destruct() {
      if ($this->connection) {
        $this->connection->close();
      }
    }
  }
