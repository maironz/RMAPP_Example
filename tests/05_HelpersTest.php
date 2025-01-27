<?php
// 05_HelpersTest.php
declare(strict_types=1);
namespace RMapp\Tests;
use RMapp\Helpers\UsersHelper;
echo "<br><b> Inizio 05_HelpersTest</b>";

require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');
use RMapp\Config\Settings;
use RMapp\helpers\Helpers;
use \Exception;
try {
  $set = Settings::getInstance();
  $param = $set->get('PP');
	$session_id = session_id();

  // Test getUserId
  echo "<br><b> Test getUserId start</b><br>";
  $userID = UsersHelper::getUserId($param,$session_id);
  echo "userID: ".$userID;
  if($userID > 0){
    echo "<br><b>Test getUserId OK</b>";
  }else{
    echo "<br><b style='color:red'>Test getUserId KO</b><br>";
  }

  // Test getUserGroupIds
  echo "<br><b> Test getUserGroupIds start</b>";
  $getUserGroupIds = UsersHelper::getUserGroupIds($param,$userID);
  echo "<br>getUserGroupIds: <br>";
  var_dump($getUserGroupIds);
  if($getUserGroupIds > 0){
    echo "<br><b>Test getUserGroupIds OK</b>";
  }else{
    echo "<br><b style='color:red'>Test getUserGroupIds KO</b>";
  }

  //Test getGroupName
  echo "<br><b> Test getGroupName start</b><br>";
  foreach($getUserGroupIds as $key => $value){
    echo " GroupNumber: ".$value;
    $getGroupName = UsersHelper::getGroupName($param,$value);
    echo " GroupName: '".$getGroupName . "' / ";
  }
  if($getGroupName > 0){
    echo "<br><b>Test getGroupName OK</b>"; 
  }else{
    echo "<br><b style='color:red'>Test getGroupName KO</b>";
  }

  //Test checkUserGroup
  echo "<br><b> Test checkUserGroup start</b>";
  $checkUserGroup = UsersHelper::checkUserGroup($param,'Administrator');
  echo "<br>checkUserGroup('Administrator') =". ($checkUserGroup == true ? 'true' : 'false');
  $checkUserGroup = UsersHelper::checkUserGroup($param,'pippo');
  echo "<br>checkUserGroup('pippo') =". ($checkUserGroup == true ? 'true' : 'false');
  $checkUserGroup = UsersHelper::checkUserGroup($param,'Super User');
  echo "<br>checkUserGroup('Super User') =". ($checkUserGroup == true ? 'true' : 'false');
  $checkUserGroup = UsersHelper::checkUserGroup($param,'Super Users');
  echo "<br>checkUserGroup('Super Users') =". ($checkUserGroup == true ? 'true' : 'false');

  echo "<br><b> Test validatePositiveInt start</b>";
  echo "<br>validatePositiveInt(0)=". (Helpers::validatePositiveInt(0) ? "true" : "false");
  echo "<br>validatePositiveInt(0.1)=". (Helpers::validatePositiveInt(0.1) ? "true" : "false");
  echo "<br>validatePositiveInt('0,1')=". (Helpers::validatePositiveInt('0,1') ? "true" : "false");
  echo "<br>validatePositiveInt(false)=". (Helpers::validatePositiveInt(false) ? "true" : "false");
  echo "<br>validatePositiveInt(true)=". (Helpers::validatePositiveInt(true) ? "true" : "false");
  echo "<br>validatePositiveInt(0x29)=". (Helpers::validatePositiveInt(0x0) ? "true" : "false");
  echo "<br>validatePositiveInt('a')=". (Helpers::validatePositiveInt('a') ? "true" : "false");
  echo "<br>validatePositiveInt(-1)=". (Helpers::validatePositiveInt(-1) ? "true" : "false");
  echo "<br>validatePositiveInt(10000)=". (Helpers::validatePositiveInt(10000) ? "true" : "false");
  echo "<br><b>Test checkUserGroup OK</b>";

  echo "<b><br> Fine 05_HelpersTest <br> Risultato OK <br></b>";
} catch (Exception $e) {
  $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
  $messaggio .= " Messaggio=".$e->getMessage();
  $messaggio .= " Stack=".$e->getTraceAsString();
  echo $messaggio;
  echo "<b style='color:red'><br> Fine 05_HelpersTest <br> Risultato Non OK <br></b>";
}

