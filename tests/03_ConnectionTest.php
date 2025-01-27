<?php
// 03_ConnectionTest.php
namespace RMapp\Tests;

echo "<br><b> Inizio 03_ConnectionTest</b>";
require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');
use RMapp\Config\Settings;
use RMapp\System\Connection;
use \Exception;
$set = Settings::getInstance();
$param = $set->get('PP');

//Connection test
try{
  $conn = new Connection();
  $db = $conn->connectDBExtra($param);
  echo "<b><br> Fine 03_ConnectionTest <br> Risultato OK <br></b>";
} catch (Exception $e){
  echo "Caught exception=".$e->getMessage() . "\r\n";
  echo "<b style='color:red'><br> Fine 03_ConnectionTest <br> Risultato Non OK <br></b>";
}
?>