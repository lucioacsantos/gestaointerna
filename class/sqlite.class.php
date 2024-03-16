<?php
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

/* Classe SQLite */
class SQLite
{
    /* Declaração de Variáveis */
    private $db;
    public  $num_rows;
    public  $last_id;
    public  $aff_rows;
    public function __construct()
    {
        $path = dirname(__FILE__) . '';
        $this->db = new SQLite3("$path/../config/gestaointerna.html");
        if (!$this->db) exit();
    }
    public function close()
    {
        $this->db->close();
    }
    /** Retorna uma linha como objeto */
    public function getRow($sql)
    {
        $result = $this->db->query($sql);
        $data= array();
        // Fetch Associated Array (1 for SQLITE3_ASSOC)
        while ($res= $result->fetchArray(1)){
            array_push($data, $res);
        }
        return $data;
        // return $result->fetchArray();
    }
    /** Retorna array com várias linhas */
    public function getRows($sql)
    {
        $result = $this->db->query($sql);
        $data= array();
        // Fetch Associated Array (1 for SQLITE3_ASSOC)
        while ($res= $result->fetchArray(1)){
            array_push($data, $res);
        }
        return $data;
    }
    // UPDATE, DELETE e CREATE TABLE
    /** Retorna número de linhas afetadas */
    public function exec($sql)
    {
        $result = $this->db->query($sql);
        return $result;
    }
}

?>