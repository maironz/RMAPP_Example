<?php
// ContractView.php
namespace RMapp\src\Contracts;
use RMapp\Helpers\LangHelper;
use RMapp\src\ControllerInterface;
use RMapp\Helpers\Helpers;
use RMapp\System\LogPHP;
use Exception;

class ContractView implements ControllerInterface {
  private $contracts;
  private $langHelperPHP;
  private $langHelperJS;
  
  public function __construct($contracts, LangHelper $langHelperPHP, LangHelper $langHelperJS){
    $this->contracts = $contracts;
    $this->langHelperPHP = $langHelperPHP;
    $this->langHelperJS = $langHelperJS;
  }
  
  // Render the view
  public function render() : void{
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPhp;
    try {
      $contratti = $this->contracts;
      $langHelperPHP = $this->langHelperPHP;
      // Get the language
      $langhelperJS = $this->langHelperJS;
      //$langhelperJS->getTranslations() in file php
      include __DIR__ . '/../../public/Contracts.php';
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " non è stato possibile effettuare il render della vista";
      $messaggio .= " e->Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
  }

  //set the language helper
  public function setLanguage(LangHelper $langHelperPHP, LangHelper $langHelperJS) : void{
    $this->langHelperPHP = $langHelperPHP;
    $this->langHelperJS = $langHelperJS;
  }
}