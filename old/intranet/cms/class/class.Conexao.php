<?php
  class Conexao {
    private static $dbtype   = "mysql";
    private static $host     = "189.126.106.70";
    private static $port     = "3306";
    private static $user     = "fox3";
    private static $password = "f0x1990";
    private static $db       = "italinea_lp";
     
    /*Metodos que trazem o conteudo da variavel desejada
    @return   $xxx = conteudo da variavel solicitada*/
    private function getDBType()  {return self::$dbtype;}
    private function getHost()    {return self::$host;}
    private function getPort()    {return self::$port;}
    private function getUser()    {return self::$user;}
    private function getPassword(){return self::$password;}
    private function getDB()      {return self::$db;}
     
    function connect(){
        try
        {
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
            ];
            $this->conexao = new PDO($this->getDBType().":host=".$this->getHost().";port=".$this->getPort().";dbname=".$this->getDB(), $this->getUser(), $this->getPassword(), $options);
        }
        catch (PDOException $i)
        {
            //se houver exceção, exibe
            die("Erro: <code>" . $i->getMessage() . "</code>");
        }
         
        return ($this->conexao);
    }
     
    private function disconnect(){
        $this->conexao = null;
    }
     
    /*Método select que retorna um VO ou um array de objetos*/
    public function selectDB($sql,$params=null,$class=null){
        // $query=$this->connect()->prepare($sql);
        // $query->execute($params);
        // if(isset($class)){
        //     $rs = $query->fetchAll(PDO::FETCH_CLASS,$class) or die(print_r($query->errorInfo(), true));
        // }else{
        //     $rs = $query->fetchAll(PDO::FETCH_OBJ) or die(print_r($query->errorInfo(), true));
        // }
        // return $rs;

        // Novo método que retorna uma $class ou um array
        $q = $this->connect();
        $stmt = $q->prepare($sql);
        $stmt->execute();
        $res = array();
        if (isset($class)) {
            foreach($stmt as $row) {
                $res[] = $this->arrayToObject($row, $class);
            }
        } else {
            foreach($stmt as $row) {
                $res[] = $row;
            }
        }
        return $res;
    }
     
    /*Método insert que insere valores no banco de dados e retorna o último id inserido*/
    public function insertDB($sql,$params=null){
        $conexao = $this->connect();
        $query = $conexao->prepare($sql); 
        $query->execute($params);
        $rs = $conexao->lastInsertId() or die(print_r($query->errorInfo(), true));
        return $rs;
    }
     
    /*Método update que altera valores do banco de dados e retorna o número de linhas afetadas*/
    public function updateDB($sql,$params=null){
        $query=$this->connect()->prepare($sql);
        $query->execute($params);
        $rs = $query->rowCount() or 0;
        return $rs;
    }
     
    /*Método delete que excluí valores do banco de dados retorna o número de linhas afetadas*/
    public function deleteDB($sql,$params=null){
        $query=$this->connect()->prepare($sql);
        $query->execute($params);
        $rs = $query->rowCount() or die(print_r($query->errorInfo(), true));
        return $rs;
    }


    private function arrayToObject(array $array, string $class_name){
        $r = new ReflectionClass($class_name);
        $object = $r->newInstanceWithoutConstructor();
        $list = $r->getProperties();
        foreach($list as $prop){
          $prop->setAccessible(true);
          if(isset($array[$prop->name]))
            $prop->setValue($object, $array[$prop->name]);
        } 
        return $object;
    }
}
?>