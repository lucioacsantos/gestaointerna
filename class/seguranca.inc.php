<?php
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

/**
 *  
 * Classe Segurança - Contém ações para garantir a segurança geral do sistema
 * 
 * */
class Seguranca
{
    public $end_ip;
    public $data_acesso;
    public $hora_acesso;
    private $AcessoSuspeito = "Acesso suspeito";
    private $AcessoSuspeitoReincidente = "Acesso suspeito reincidente";
    private $AcessoSuspeitoBloqueado = "Bloqueado";
    private $AcessoComSucesso = "Acesso com sucesso";

    /** 
     * Registra/Atualiza dados do endereço IP suspeito
     */
    function RegAcessoSuspeito()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $this->SelectAcessoSuspeito();
        if ($row){
            if ($row->contador < 5){
                $row = $pg->exec("UPDATE db_clti.tb_acesso_suspeito SET (data_acesso,hora_acesso,contador,status) 
                    = ('$this->data_acesso','$this->hora_acesso',contador +1,'$this->AcessoSuspeitoReincidente') WHERE end_ip = '$this->end_ip'");
            }
            else{
                $row = $pg->exec("UPDATE db_clti.tb_acesso_suspeito SET (data_acesso,hora_acesso,contador,status) 
                = ('$this->data_acesso','$this->hora_acesso',contador +1,'$this->AcessoSuspeitoBloqueado') WHERE end_ip = '$this->end_ip'");
            }            
        }
        else {
            $row = $pg->insert("INSERT INTO db_clti.tb_acesso_suspeito (end_ip,data_acesso,hora_acesso,contador,status) 
                VALUES ('$this->end_ip','$this->data_acesso','$this->hora_acesso',1,'$this->AcessoSuspeito') ","idtb_acesso_suspeito");
        }
        return $row;
    }
    /** 
     * Zera contador de acessos suspeitos por IP
     */
    function ZeraContador()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->getRow("SELECT * FROM db_clti.tb_acesso_suspeito WHERE end_ip = '$this->end_ip'");
        if ($row){
            $row = $pg->exec("UPDATE db_clti.tb_acesso_suspeito SET (data_acesso,hora_acesso,contador,status) 
                = ('$this->data_acesso','$this->hora_acesso',0,'$this->AcessoComSucesso') WHERE end_ip = '$this->end_ip'");
        }        
        return $row;
    }
    /** 
     * Zera todos os contadores de acesso suspeitos
     */
    function ZeraTodosContadores()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->exec("UPDATE db_clti.tb_acesso_suspeito SET (data_acesso,hora_acesso,contador,status)
            = ('$this->data_acesso','$this->hora_acesso',0,'$this->AcessoComSucesso') ");
        return $row;
    }
    /** 
     * Verifica se endereço IP suspeito já está registrado
     */
    function SelectAcessoSuspeito()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->getRow("SELECT * FROM db_clti.tb_acesso_suspeito WHERE end_ip = '$this->end_ip'");
        return $row;
    }
    /** 
     * Verifica status Bloqueado do IP acessando o sistema 
     */
    function ChecaBloqueado()
    {
        require_once "pgsql.class.php";
        $pg = new PgSql();
        $row = $pg->getRow("SELECT * FROM db_clti.tb_acesso_suspeito WHERE end_ip = '$this->end_ip'
            AND status = 'Bloqueado'");
        return $row;
    }
    /***
     * Recupera informações de secret keys
     */
    function Get_Secret(){
        require_once "sqlite.class.php";
        $sq = new SQLite();
        $row = $sq->getRow("SELECT * FROM tb_parametros ");
        return $row;
    }
    /** 
     * Obtém endereço IP suspeito
     */
    function GetIP()
    {
        ob_start();
        $ipaddress = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
            getenv('HTTP_X_FORWARDED')?:
            getenv('HTTP_FORWARDED_FOR')?:
            getenv('HTTP_FORWARDED')?:
            getenv('REMOTE_ADDR');
        return $ipaddress;
    }
}

/** Classe de criptografia de dados */
class CriptoDados
{
    private $encryptMethod = "AES-256-CBC";
    private $key1;
    private $key2;
    private $key3;

    public $var1;
    public $var2;
    public $var3;
    public $var4;
    public $var5;
    public $var6;

    /** Criptografa e retorna dados */
    function Executa()
    {
        $this->Prepara();
        eval($this->Verifica());
        $this->var6 = (object)['var5'=>"$this->var5", 'var4'=> "$this->var4", 'var3'=>"$this->var3"];
        return $this->var6;
    }
    /** Checa criptografia */
    function Verifica()
    {
        $this->key = $this->key1;
        $this->ivalue = $this->key2;
        $this->output = $this->key3;
        $return = openssl_decrypt($this->output, $this->encryptMethod, $this->key, 0, $this->ivalue);
        return $return;
    }
    /** Prepara criptografia */
    function Prepara()
    {
        $keys = $this->Autor();
        $this->key1 =  hash('sha256', ($keys['author']));
        $this->key2 = substr(hash('sha256', ($keys['generator'])), 0, 16);
        $this->key3 = 'rVvk4gUs7fiyxLwDjNDzFJc0kJx4xBwyKdwUs49Lor8YABeCY5o
                        G9L9k64mqIiA7WW3rdG5Lq7VnuRSEanrU2nCqMFZnmHoBbSw8Q
                        RiQYxZ+mb+dTCc2FQXGJly3R/Ix99XmmouIUZqgNXslg4ui7B4
                        UbWxKjJ5EFPlyivM3RqY7ny8Qab6JdJtJOH1JJ1vO';
    }
    /** Recupera meta tags */
    function Autor()
    {
        require_once "constantes.inc.php";
        $config = new Config();
        $tags = array();
        foreach ($config->SelectTags() as $item){
            $tags[$item['parametro']] = $item['valor'];
        }
        return $tags;
    }
}

/** Classe de decriptografia de dados */
class DecriptoDados
{
    private $encryptMethod = "AES-256-CBC";
    private $key1;
    private $key2;
    private $key3;

    public $var1;
    public $var2;
    public $var3;
    public $var4;
    public $var5;
    public $var6;

    /** Criptografa e retorna dados */
    function Executa()
    {
        $this->Prepara();
        eval($this->Verifica());
        $this->var6 = (object)['var5'=>$this->var5, 'var1'=> $this->var1, 'var2'=>$this->var2];
        return $this->var6;
    }
    /** Checa criptografia */
    function Verifica()
    {
        $this->key = $this->key1;
        $this->ivalue = $this->key2;
        $this->output = base64_encode($this->key3);
        $return = openssl_decrypt(base64_decode($this->output), $this->encryptMethod, 
            $this->key, 0, $this->ivalue);
        return $return;
    }
    /** Prepara criptografia */
    function Prepara()
    {
        $keys = $this->Autor();
        $this->key1 =  hash('sha256', ($keys['author']));
        $this->key2 = substr(hash('sha256', ($keys['generator'])), 0, 16);
        $this->key3 = 'XDlVpPiOBnbF3uV6jAONwHy16KD7ex4qaxmx7R2kCAow6ivP25
                        xsExePurKKsWraINnySVpyLNHnKyk7V3NzZgWWHXnN68t6GwL
                        9rHkAyul7J0ZOnDsdkSJ18LoYb1VKqr+3quCdCKkfei2no7T7
                        D0eF1KyntzPd0ewC90298TXJ5knDdIutjdCqYUnAZ9Ju';
    }
    /** Recupera meta tags */
    function Autor()
    {
        require_once "constantes.inc.php";
        $config = new Config();
        $tags = array();
        foreach ($config->SelectTags() as $item){
            $tags[$item['parametro']] = $item['valor'];
        }
        return $tags;
    }
}

?>