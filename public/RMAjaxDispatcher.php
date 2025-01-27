<?php
//  RMapp/public/RMAjaxDispatcher.php

// Custom variables
require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');

//  This file is the entry point for all AJAX requests.
//  It is responsible for routing the request to the appropriate controller.
use RMapp\Config\Settings;
use RMapp\System\LogPHP;
use RMapp\Helpers\UsersHelper;
use RMapp\src\Contracts\ContractController;
use RMapp\Helpers\Helpers;
use RMapp\Helpers\LangHelper;

$set = Settings::getInstance();
$param = $set->get('PP');
$error = new LogPHP;
$userid = 0;
$usergrouplist = array();

//checkUserGroupId($param,$grouptofind,$usergrouplist) per verificare se l'utente appartiene a un gruppo
//$superUserIds in settings1.php è un array con gli ID utenti autorizzati

/*
come funziona
questo file è programmato per ricevere input in formato JSON
il file JSON deve avere una configurazione specifica
deve assolutamente contenere
cookie
sessionName
method  in base al metodo cambia la funzione
altri parametri da passare alla funzione

*/
try{
	$content = file_get_contents("php://input");
	Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
	$v = json_decode(stripslashes($content));
	$sname = "";
	$sname = session_name($v->sessionName);
	session_start();
	$session_id = session_id();
  $langHelperPHP = new LangHelper('php');
  $PHPTranslations = $langHelperPHP->getLanguage();
  $langHelperJS = new LangHelper('js');
  $JSTranslations = $langHelperJS->getTranslations();

	//check v is null
	if (!is_null($v)){
		if (!is_null($v->cookie)) {
			$cookie = $v->cookie;
			if($cookie !== $session_id){
				$messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
				$messaggio .= " cookie !== session_id";
				$messaggio .= " cookie =".$cookie;
				$messaggio .= " session_id =".$session_id;
				$messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
				$error-> write($messaggio);
				header('Location: '. "/");
			}
		} else {//v->cookie is null, someone is trying to do something
			$messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
			$messaggio .= " v->cookie è nullo qualcuno sta cercando di fare qualcosa";
			//Todo: add IP verification
			$messaggio .= " content = ".$content;
			$messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
			$error-> write($messaggio);
      header('Location: '. "/");
		}

		if (!is_null($v->method)) {// Null method check
			// Get User ID from DB
			$userid = UsersHelper::getUserId($param);
			$method = $v->method;
			if(UsersHelper::checkSuperUser($param)){// Administrative auth check
				/** Method check */
				$contractController = new ContractController($langHelperPHP,$langHelperJS);
				if ($method == "updateContract"){
					$contractController->updateContract($param,$v->id,$v->field,$v->value);
				} else if($method == "newContract"){
					$contractController->newContract($param);
				}
			} else { // not administrator
				die('Utente non autorizzato');
			}
    } else {//$v->method is null
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " v->method è nullo";
      $messaggio .= " content = ".$content;
			$messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $resp = json_encode(array("respStatus"=>0, "descRespStatus"=>"method è nullo"));
      $error-> write($messaggio);
      echo $resp;
    }
  } else {//$v is null
    $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
    $messaggio .= " v è nullo tentativo di accesso diretto";
    $messaggio .= " session_name = $sname";
		$messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
		//TODO: add IP
    $error-> write($messaggio);
    header('Location: '. "/");
  }
} catch (Exception $e) {
	$messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
	$messaggio .= " Messaggio=".$e->getMessage();
	$messaggio .= " Stack=".$e->getTraceAsString();
	$messaggio .= " probabile assenza di sessionname='".$sname."'";
	$messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
	$error-> write($messaggio);
	header('Location: '. "/");
}