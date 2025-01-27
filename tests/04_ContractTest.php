<?php
// 04_ContractTest.php
declare(strict_types=1);
namespace RMapp\Tests;
echo "<br><b> Inizio 04_ContractTest</b>";

require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');
use RMapp\Config\Settings;
use RMapp\System\LogPHP;
use RMapp\src\Contracts\ContractService;
use RMapp\Helpers\LangHelper;
use \Exception;
$error = new LogPHP;
try{
  testGetcontracts();
} catch (Exception $e) {
  $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
  $messaggio .= " Messaggio=".$e->getMessage();
  $messaggio .= " Stack=".$e->getTraceAsString();
  $error-> write($messaggio);
  echo $messaggio;
}

function testGetcontracts(){
  $set = Settings::getInstance();
  $param = $set->get('PP');
  $language = $set->get('language');
  $langHelperPHP = new LangHelper('php');
  $contratti = [];
  try{
    // Test getContracts
    echo "<br><b> Test getContracts start</b><br>";
    $contractServices = new ContractService($langHelperPHP);
    $contratti = $contractServices->getContracts($param);
    var_dump($contratti[0]);

    echo "<b><br> Fine 04_ContractTest <br> Risultato OK <br></b>";
  } catch (Exception $e) {
    $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
    $messaggio .= " Messaggio=".$e->getMessage();
    $messaggio .= " Stack=".$e->getTraceAsString();
    echo $messaggio;
    echo "<b style='color:red'><br> Fine 04_ContractTest <br> Risultato Non OK <br></b>";
  }
}
?>