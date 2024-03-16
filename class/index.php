<?php 
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

/** Registra tentativa de acesso suspeito */
include "acesso_suspeito.inc.php"; 
$msg = AcessoSuspeito("Ocorreu algum erro, por favor aguarde!"); 
Mensagens($msg); 

?>