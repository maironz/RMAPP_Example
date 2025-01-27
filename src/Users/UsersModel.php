<?php
// UsersModel.php
declare(strict_types=1);
namespace RMapp\obj\Users;
use RMapp\Config\Settings;

class UsersModel
{
  private $set;
  protected $users_table;
  protected $users_model;
  protected $users_model_detail;
  protected int $userID;
  protected string $userName;
  protected array $userGroups;
  protected string $sessionID;

  public function __construct($userID){
    $this->set = Settings::getInstance();
    $this->userID = $userID;
  }

  public function getUserID() : int {
    return $this->userID;
  }

  public function getUserName() : string {
    return $this->userName;
  }

  public function setUserName(string $userName) : void {
    $this->userName = $userName;
  }

  public function getUserGroups() : array {
    return $this->userGroups;
  }

  public function setUserGroups(array $userGroups) : void {
    $this->userGroups = $userGroups;
  }

  public function getSessionID() : string {
    return $this->sessionID;
  }

  public function setSessionID(string $sessionID) : void {
    $this->sessionID = $sessionID;
  }

  
}