<?php
// Settings.php
namespace RMapp\Config;
// singleton class
/* example of use:
  use App\Config\Settings;
  $settings = Settings::getInstance();
  $settings->get('DB_USER');
*/
class Settings {
  private static $instance = null;// Contain the only instance of the class
  private $settings = [];         // Array to store configurations
  // Constructor private to avoid external instances
  private function __construct() {
    // Set the initial configurations
    $this->settings = [
      'LOCALDEBUG' => true,
      'DB_USER' => '',
      'DB_PASS' => '',
      'DB_NAME' => '',
      'TABLE_PREFIX' => '',
      'FIELD_PREFIX' => '',
      'SEPARATOR' => '-_-_-', // standard symbol to create accumulated data in a single string
      'MITIGATE_DDOS' => false, // to mitigate ddos
      'PP' => '', // security string
      'MAIL_TO' => '', // admin mail
      'LOG_FILE_NAME' => str_replace('\\', '/',__DIR__ . '/../Config/LogData.php'), // log file name with path
      'language' => 'en', // field to mark as deleted
      'deleteparam' => 'cancellato', // field to mark as deleted
      'ERROR_TABLE' => 'cassa_errori', // table to store errors
      'tables_users' => 'users',
      'tables_contracts' => 'a_tipi_contratti',
      'tables_employee_registry' => 'a_dipendenti_anagrafica',
      'tables_employee_contracts' => 'a_dipendenti_contratti'
    ];
    if ($this->settings['LOCALDEBUG']) {
      $this->settings['DB_HOST'] = 'localhost:3306';
    } else {
      $this->settings['DB_HOST'] = 'localhost';
    }
  }
  // Methods to get the instance
  public static function getInstance() {
    if (self::$instance === null) {
        self::$instance = new Settings();
    }
    return self::$instance;
  }
  
  // Methods to get a configuration value
  public function get($key) {
    return $this->settings[$key] ?? null; // return null if the key does not exist
  }
}
?>