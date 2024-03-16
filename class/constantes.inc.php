<?php
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

/** TODO: Documentar Classes/Funções */

/* Inicializa Sessão */
session_start();

/* Função para Verificar Login */
/* function isLoggedIn(){
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
        return false;
    }
    return true;
} */

/** Classe Configurações */
class Config
{
   
    /** Selectiona todas as configurações */
    function SelectAll()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRows("SELECT * FROM tb_config ORDER BY idtb_config ");
        return $row;
    }
    /** Seleciona a URL a partir do banco para uso no html */
    function SelectURL()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT valor FROM tb_config WHERE parametro='URL'");
        return $row[0]['valor'];
    }
    /** Seleciona o Título */
    function SelectTitulo()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT valor FROM tb_config WHERE parametro='TITULO'");
        return $row[0]['valor'];
    }
    /** Seleciona meta tags */
    function SelectTags()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRows("SELECT * FROM tb_config WHERE parametro='author' OR parametro='description' OR parametro='generator' ");
        return $row;
    }
    /** Seleciona versão */
    function SelectVersao()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT valor FROM tb_config WHERE parametro='VERSAO'");
        return $row;
    }
    /** Seleciona estado */
    function SelectEstado()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT * FROM tb_config WHERE parametro='ESTADO'");
        return $row;
    }
    /** Seleciona cidade */
    function SelectCidade()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT * FROM tb_config WHERE parametro='CIDADE'");
        return $row;
    }
    /** Atualiza configurações */
    function UpdateConfig()
    {
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->exec("UPDATE tb_config SET valor = '$this->valor' WHERE idtb_config = '$this->idtb_config'");
        return $row;
    }

}

/** Classe Órgão Partidários */
class Orgaos
{

    /** Selectiona propostas de inativação */
    function SelectPropostasInativacao()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->getRows("SELECT * FROM vw_bot_proposta_inativacao ");
        return $row;
    }
    /** Selectiona propostas de anotação */
    function SelectPropostasAnotacao()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->getRows("SELECT * FROM vw_bot_propostas_anotacoes ");
        return $row;
    }
    
}

/** Classe Parâmetros */
class Parametros
{
    public $parametro;
    public $valor;
    
    /** Insere parâmetros */
    function InsereParametros(){
        require_once 'sqlite.class.php';
        $sq = new SQLite();
        $row = $sq->exec("INSERT INTO tb_parametros (parametro,valor) VALUES ('$this->parametro','$this->valor')");
        return $row;
    }
}