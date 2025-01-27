<?php
// UsersServices.php
namespace RMapp\Users;
use RMapp\Config\Settings;

class UsersServices
{
  private $set;
  protected $users_table;
  public function __construct($users_table){
    // Get the settings instance
    $this->set = Settings::getInstance();
    // Set the users table
    $this->users_table =  $this->set->get('TABLE_PREFIX') . $this->set->get('tables_users') ;
  }

  public function getAllUsers()
  {
    // Implement logic to retrieve all users
  }

  public function getUserById($id)
  {
    // Implement logic to retrieve a user by their ID
  }

  public function createUser($userData)
  {
    // Implement logic to create a new user
  }

  public function updateUser($id, $userData)
  {
    // Implement logic to update an existing user
  }

  public function deleteUser($id)
  {
    // Implement logic to delete a user
  }
}