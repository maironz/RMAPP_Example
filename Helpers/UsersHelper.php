<?php
// UsersHelpers.php
namespace RMapp\Helpers;

use RMapp\System\LogPHP;
use RMapp\System\Connection;
use RMapp\Config\Settings;
use \Exception;

class UsersHelper{

  // Joomla user and group functions
  // Function that retrieves the user_id from the session_id in the database
  static function getUserId(string $param) : int{
    $set = Settings::getInstance();
    $error = new LogPHP;
		if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $error-> write($messaggio);
      die;
    }
    $retval = 0;
    try{
      $table = "`" . $set->get('TABLE_PREFIX') . "session`";
      $session_id = session_id();
    
      $conn = new Connection;
      $db1 = $conn->connectDBExtra($param);
      $query = "SELECT userid FROM $table WHERE `session_id`=?";
      
      $var=array($session_id);
      if($stmt = mysqli_prepare($db1, $query)){
        //aggiungo il numero di"s" sufficienti
        //$types = str_repeat('s', count($var));
        mysqli_stmt_bind_param($stmt, 's', ...$var);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
          $retval = $row["userid"];
        }
        mysqli_stmt_close($stmt);
        $stmt=null;
      } else {
        $messaggio  = "Prepare failed: (" . mysqli_errno($db1) . ") " . mysqli_error($db1);
        $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " query=".$query;
        $messaggio .= " var=".implode("---", $var);
        $error-> write($messaggio);
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack=".$e->getTraceAsString();
      $error-> write($messaggio);
    }
    mysqli_close($db1);
    return $retval;
  }

  // Function that retrieves the user group ids from the userid in the database
  static function getUserGroupIds(string $param,int $userid) : array{
    $set = Settings::getInstance();
    $error = new LogPHP;
		if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $error-> write($messaggio);
      die;
    }
    $retval = array();
    if(!Helpers::validatePositiveInt($userid)) return $retval;
    
    try{
      $table = "`" . $set->get('TABLE_PREFIX') . "user_usergroup_map`";
      $conn = new Connection;
      $db1 = $conn->connectDBExtra($param);
      $query = "SELECT group_id FROM $table WHERE `user_id`=?";
      
      $var=array($userid);
      if($stmt = mysqli_prepare($db1, $query)){
        //aggiungo il numero di"s" sufficienti
        //$types = str_repeat('s', count($var));
        mysqli_stmt_bind_param($stmt, 's', ...$var);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
          $retval[] = $row["group_id"];
        }
        mysqli_stmt_close($stmt);
        $stmt=null;
      } else {
        $messaggio  = "Prepare failed: (" . mysqli_errno($db1) . ") " . mysqli_error($db1);
        $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " query=".$query;
        $messaggio .= " var=".implode("---", $var);
        $error-> write($messaggio);
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack=".$e->getTraceAsString();
      $error-> write($messaggio);
    }
    mysqli_close($db1);
    return $retval;
  }
  
  // Function that returns the description of the group
  static function getGroupName(string $param,int $group) : string{
    $set = Settings::getInstance();
    $error = new LogPHP;
		if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $error-> write($messaggio);
      die;
    }
    $retval = "";
    if($group == 0) return $retval;
    try{
      $tablegroups = "`" . $set->get('TABLE_PREFIX') . "usergroups`";
      $conn = new Connection;
      $db1 = $conn->connectDBExtra($param);
      $query = "SELECT title FROM $tablegroups WHERE `id`=?";
      
      $var=array($group);
      if($stmt = mysqli_prepare($db1, $query)){
        //aggiungo il numero di"s" sufficienti
        //$types = str_repeat('s', count($var));
        mysqli_stmt_bind_param($stmt, 's', ...$var);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
          $retval = $row["title"];
        }
        mysqli_stmt_close($stmt);
        $stmt=null;
      } else {
        $messaggio  = "Prepare failed: (" . mysqli_errno($db1) . ") " . mysqli_error($db1);
        $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " query=".$query;
        $messaggio .= " var=".implode("---", $var);
        $error-> write($messaggio);
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack=".$e->getTraceAsString();
      $error-> write($messaggio);
    }
    mysqli_close($db1);
    return $retval;
  }
  
  // Function that verifies the user's membership in the group by name
  static function checkUserGroup(string $param, string $usergroup) : bool{
    $retbool = false;
    $set = Settings::getInstance();
    $error = new LogPHP;
		if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $error-> write($messaggio);
      die;
    }
    switch ($usergroup) {
      case 'Administrator':
      case 'Super Users':
      case 'Public':
      case 'Registered':
      case 'Author':
      case 'Editor':
      case 'Publisher':
      case 'Manager':
      case 'Guest':
      case 'Cassa1':
      case 'Cassa2':
      case 'Cassa3':
      case 'Cassa4':
      case 'Cassa5':
      case 'Cassa6':
      case 'Studio':
      case 'Dipendenti':
      case 'Fausto':
      case 'Fausto':
        break;
      default:
        return false;
    }
    try{
      $userid = UsersHelper::getUserId($param);
      $conn = new Connection();
      $db = $conn->connectDBExtra($param);
      $adminID = null;
      $query = "SELECT id FROM ".$set->get('TABLE_PREFIX')."usergroups WHERE title = '$usergroup';";
      if($stmt = mysqli_prepare($db, $query)){
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)){
          $adminID = $row['id'];
        }
        mysqli_stmt_close($stmt);
        $stmt=null;
      } else {
        $messaggio  = "Prepare failed: (" . mysqli_errno($db) . ") " . mysqli_error($db);
        $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " query=".$query;
        $error-> write($messaggio);
      }
      
      // get the user_id from the user_usergroup_map table where the group_id is the adminID and the user_id is the userid
      $query = "SELECT user_id FROM ".$set->get('TABLE_PREFIX')."user_usergroup_map WHERE group_id = $adminID AND user_id = $userid;";
      $result = mysqli_query($db, $query);
      while($row = mysqli_fetch_assoc($result)){
        $retbool = true;// if a row is present, it means that the logged in user belongs to the specified group
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio = ".$e->getMessage();
      $messaggio .= " query = ".$query;
      $messaggio .= " usergroup = ".$usergroup;
      $messaggio .= " Stack = ".$e->getTraceAsString();
      $error-> write($messaggio);
      header('Location: '. "/");
    }
    mysqli_close($db);
    return $retbool;
  }

  // Function that verifies the user's membership in the group by id
  static function checkSuperUser(string $param) : bool{
    $error = new LogPHP;
    if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $error-> write($messaggio);
      die;
    }
    $superUserGroups = ['Administrator', 'Super Users', 'Fausto'];
    foreach ($superUserGroups as $group) {
      if (UsersHelper::checkUserGroup($param, $group)) {
        return true;
      }
    }
    return false;
  }
}