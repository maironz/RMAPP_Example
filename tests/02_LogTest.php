<?php
// 02_LogTest.php
namespace RMapp\Tests;
echo "<br><b> Inizio 02_LogTest</b><br>";

require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');
use RMapp\System\LogPHP;
use \Exception;

// AutoloaderTest fino qui funziona

// Log test
try{
  $error = new LogPHP;
  $messaggio  = "02_LogTest.php";
  $error->writeTxt($messaggio);
  echo "Se il test ha funzionato dovresti trovare il messaggio '02_LogTest.php' nel file RMapp/Config/LogData.php";
  echo "<b><br> Fine 02_LogTest <br> Risultato OK <br></b>";
} catch (Exception $e){
  echo "Caught exception=".$e->getMessage() . "\r\n";
  echo "<b style='color:red'><br> Fine 02_LogTest <br> Risultato Non OK <br></b>";
}

?>