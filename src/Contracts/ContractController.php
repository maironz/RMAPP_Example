<?php
// ContractController.php
// Manage transactions with the Ajax Dispatcher for contracts and execute the views.
declare(strict_types=1);
namespace RMapp\src\Contracts;

use Exception;
use RMapp\Config\Settings;
use RMapp\System\LogPHP;
use RMapp\Helpers\UsersHelper;
use RMapp\Helpers\Helpers;
use RMapp\Helpers\LangHelper;
use RMapp\src\ControllerInterface;
use RMapp\src\Contracts\ContractView;

class ContractController implements ControllerInterface {
  private ContractService $contractService;
  private LangHelper $langHelperPHP;
  private LangHelper $langHelperJS;
  private string $resp = "";

  public function __construct(LangHelper $langHelperPHP,LangHelper $langHelperJS) {
    $this->contractService = new ContractService($langHelperPHP);
    $this->langHelperPHP = $langHelperPHP;
    $this->langHelperJS = $langHelperJS;
  }

  public function setLanguage(LangHelper $langHelperPHP,LangHelper $langHelperJS) : void {
    $this->langHelperPHP = $langHelperPHP;
    $this->langHelperJS = $langHelperJS;
  }


  private function checkUserAuth($param): bool{
    $retval = false;
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPhp;
    try {
      // check the user authorization
      if(! UsersHelper::checkSuperUser($param) ){
        $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " Utente non autorizzato id=" . UsersHelper::getUserId($param);
        $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
        $error-> write($messaggio);
        throw new Exception("Unauthorized access or invalid security code");
        die;
      }
      $retval = true;
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " non è stato possibile effettuare il render della vista";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
    return $retval;
  }

  // Prepare the contracts and render the view
  public function render() : void {
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPhp;
    $param = Settings::getInstance()->get('PP');
    try {
      // check the user authorization
      if($this->checkUserAuth($param)){
        $contracts = $this->contractService->getContracts($param);
        $view = new ContractView($contracts, $this->langHelperPHP, $this->langHelperJS);
        $view->render();
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " non è stato possibile effettuare il render della vista";
      $messaggio .= " e->Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
  }

  // Update the contract from ajax
  public function updateContract(String $param,$id,$field,$value) : void {
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPhp;
    $param = Settings::getInstance()->get('PP');
    $this->resp = json_encode(array("respStatus"=>0, "descRespStatus"=>$this->langHelperPHP->translate("null_value")));
    try {
      if($this->checkUserAuth($param)){
        $this->contractService->updateContract($param,$id,$field,$value);
        $this->resp = json_encode(array("respStatus"=>1, "descRespStatus"=>$this->langHelperPHP->translate("contract_mod_ok")));
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " this->contractService->updateContract è andato in errore";
      $messaggio .= " verificare id=$id, field=$field, value=$value";
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
      $this->resp = json_encode(array("respStatus"=>1, "descRespStatus"=>$this->langHelperPHP->translate("contract_error")));
    }
    echo $this->resp;
  }

  // Add new contract
  public function newContract(String $param) : void {
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPhp;
    $this->resp = json_encode(array("respStatus"=>0, "descRespStatus"=>$this->langHelperPHP->translate("null_value")));
    try {
      if($this->checkUserAuth($param)){
        $ID = $this->contractService->newContract($param);
        $this->resp = json_encode(array("respStatus"=>1, "descRespStatus"=>$this->langHelperPHP->translate("new_contract_ok") . " ID = " . strval($ID)));
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " this->contractService->newContract è andato in errore";
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
      $this->resp = json_encode(array("respStatus"=>1, "descRespStatus"=>$this->langHelperPHP->translate("new_contract_error")));
    }
    echo $this->resp;
  } 

}