<?php
// ContractServices.php
// Manage transactions with the database for contracts.
declare(strict_types=1);
namespace RMapp\src\Contracts;
use RMapp\Config\Settings;
USE RMapp\System\Connection;
use RMapp\System\LogPHP;
use RMapp\src\Contracts\ContractModel;
use RMapp\Helpers\Helpers;
use RMapp\Helpers\LangHelper;
use Exception;
USE mysqli_result;

class ContractService{
  private $set;
  protected $contract_model;
  protected $contract_table;
  protected $deleteparam;
  protected $deletecheckquery;
  private $langHelperPHP;

  public function __construct(LangHelper $langHelperPHP){
    // Get the settings instance
    $this->set = Settings::getInstance();
    // Set the contract table
    $this->contract_table =  $this->set->get('TABLE_PREFIX') . $this->set->get('tables_contracts') ;
    // Set the delete parameter
    $this->deleteparam = $this->set->get('deleteparam');
    // Set the delete check query
    $this->deletecheckquery = " WHERE `{$this->set->get('deleteparam')}`= 0";
    $this->langHelperPHP = $langHelperPHP;
  }

  // Function to get all contracts from DB
  public function getContracts($param,$notdeleted = true) : array {
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPHP;
    try{
      // Create a new connection
      $conn = new Connection();
      // Connect to the database
      $db = $conn->connectDBExtra($param);
      // Set the query extra
      if ($notdeleted) $queryextra = $this->deletecheckquery;
      // Set the query
      $query = "SELECT * FROM `{$this->contract_table}` {$queryextra} ";
      // Get the database query
      $result = $conn->getDBQueryDBOpened($param,$query,$db);
      // Loop through the results
      $contracts = [];
      while ($row = mysqli_fetch_assoc($result)) {
        // Create a new contract model
        $this->contract_model = new ContractModel();
        $this->contract_model->setId($row["id"]);
        $this->contract_model->setDescrizione($row["descrizione"]);
        $this->contract_model->setOresettimanali(intval($row["oresettimanali"])) ;
        $this->contract_model->setGiorniFerieAnnuali(intval($row["giorni_ferie_annuali"])) ;
        $this->contract_model->setOrePermessoAnnuali(intval($row["ore_permesso_annuali"])) ;
        $this->contract_model->setOraInizioEstivo($row["ora_inizio_estivo"]);
        $this->contract_model->setOraFineEstivo($row["ora_fine_estivo"]);
        $this->contract_model->setPausaInizioEstivo($row["pausa_inizio_estivo"]);
        $this->contract_model->setDurataPausaEstivo($row["durata_pausa_estivo"]);
        $this->contract_model->setOraInizioInverno($row["ora_inizio_inverno"]);
        $this->contract_model->setOraFineInverno($row["ora_fine_inverno"]);
        $this->contract_model->setPausaInizioInverno($row["pausa_inizio_inverno"]);
        $this->contract_model->setDurataPausaInverno($row["durata_pausa_inverno"]);
        $this->contract_model->setCancellatoInt(intval($row["cancellato"]));
        $contracts[] = $this->contract_model->getContract();
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
    mysqli_close($db);
    return $contracts;
  }

  // Function to update the existing contract, return JSON with status
  function updateContract($param,$id,$field,$value){
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPHP;
		if ($param!=Settings::getInstance()->get('PP')){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " param=$param non è uguale a PP";
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
      die;
    }
    try{
      $checkField = false;
      switch ($field){
        case "descrizione":
          // Security conversion
          $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
          $checkField = true;
          break;
        case "oresettimanali":
          if (Helpers::validatePositiveInt($value)){
            if($value<128) $checkField = true;
          }
          break;
        case "giorni_ferie_annuali":
          if (Helpers::validatePositiveInt($value)){
            if($value<365) $checkField = true;
          }
          break;
        case "ore_permesso_annuali":
          if (Helpers::validatePositiveInt($value)){
            if($value<8794) $checkField = true;
          }
          break;
        case "pausa_inizio_estivo":
        case "durata_pausa_estivo":
        case "pausa_inizio_inverno":
        case "durata_pausa_inverno":
        case "ora_inizio_estivo":
        case "ora_fine_estivo":
        case "ora_inizio_inverno":
        case "ora_fine_inverno":
          if(Helpers::validateHours($value)) $checkField = true;
          break;
        case "cancellato":
          if($value == 0 || $value == 0) $checkField = true;
          break;
        default:
      }
      if(Helpers::validatePositiveInt($id)){
        $id = (int)$id;
      } else {
        $checkField = false; // use the not valid checkField to invalidate id
      }
      // if the field is valid
      if($checkField){
        if (!is_null($field) && !is_null($value)) {
          $conn = new Connection();
          $db = $conn->connectDBExtra($param);
          $query = "UPDATE `{$this->contract_table}` SET `$field`=? WHERE id=?";
          $var=array($value,$id);
          if($stmt = mysqli_prepare($db, $query)){
            mysqli_stmt_bind_param($stmt, 'si', ...$var);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $stmt=null;
          } else {
            $messaggio  = "Prepare failed: (" . mysqli_errno($db) . ") " . mysqli_error($db);
            $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
            $messaggio .= " query=".$query;
            $messaggio .= " var=".implode("---", $var);
            $messaggio .= " Modifica contratto non riuscita in 'prepare'";
            $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
            $error-> write($messaggio);
          }
          mysqli_close($db);
        } else {
          $messaggio  = "Error in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."] ";
          $messaggio .= " campo field o value non valido";
          $messaggio .= " field = ".$field;
          $messaggio .= " value = ".$value;
          $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
          $error-> write($messaggio);
        }
      } else {
        $messaggio  = "Error in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."] ";
        $messaggio .= " campo field o id non valido";
        $messaggio .= " field = ".$field;
        $messaggio .= " id = ".$id;
        $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
        $error-> write($messaggio);
        throw new Exception(Helpers::getCallStack() . Helpers::getLine(). $this->langHelperPHP->translate("field_or_id_not_valid"));
      }
    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
  }

  // Function to add new contract
  function newContract($param) : int {
    $retval = 0 ;
    Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);
    $error = new LogPHP;
    try {
      $conn = new Connection();
      $db = $conn->connectDBExtra($param);
      if ($param!=Settings::getInstance()->get('PP')){
        $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
        $messaggio .= " param=$param non è uguale a PP";
        $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
        $error-> write($messaggio);
        die;
      }
      $query1 = "INSERT INTO `{$this->contract_table}` () VALUES ()";
      $query2 = "SELECT max(id) AS id FROM `{$this->contract_table}`";
      if($conn->manageTransaction($param, $db, 'start')){
        $result1 = $conn->getDBQueryDBOpened($param, $query1, $db);
        $result2 = $conn->getDBQueryDBOpened($param, $query2, $db);
        if ($result1 !== false && $result2 instanceof mysqli_result) {
          $conn->manageTransaction($param, $db, 'commit');
          while ($row = mysqli_fetch_assoc($result2)) {
            $retval = intval($row["id"]);
          }
        } else {
          $conn->manageTransaction($param, $db, 'rollback');
        }
      }

    } catch (Exception $e) {
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
      $error-> write($messaggio);
    }
    return $retval;
    mysqli_close($db);
  }
}