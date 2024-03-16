<?php
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

require 'vendor/autoload.php';
require_once 'seguranca.inc.php';
require_once 'constantes.inc.php';

use Aws\SecretsManager\SecretsManagerClient;

$seg = new Seguranca();
$decript = new DecriptoDados;

$sec = $seg->Get_Secret();
print_r($sec);



/* $client = new Aws\SecretsManager\SecretsManagerClient([
    'region' => 'us-east-1'
]);

$result = $client->getSecretValue([
    'SecretId' => 'production/creds/Postgresql',
]);

print_r($result)
 */
?>