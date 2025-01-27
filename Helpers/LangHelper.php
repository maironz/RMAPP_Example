<?php
// LangHelper.php
namespace RMapp\Helpers;

use Exception;
use RMapp\Config\Settings;
use RMapp\System\LogPHP;

/*
example

public function __construct($contracts, LangHelper $langHelperPHP, LangHelper $langHelperJS){
  $this->contracts = $contracts;
  $this->langHelperPHP = $langHelperPHP;
  $this->langHelperJS = $langHelperJS;
}
$langhelperPHP = new LangHelper('php');
$PHPTranslations = $langhelperPHP->getLanguage();
$langhelperJS = new LangHelper('js');
$JSTranslations = $langhelperJS->getTranslations();

$langHelperPHP->translate("weekly_hours")

*/
class LangHelper {
  protected $destination;
  protected $language;
  protected $translations = [];
  protected $logger;

  // Initialize the logger, language, and translations
  public function __construct($destination) { // $destination = 'php' or 'js'
    $this->logger = new LogPHP();
    try{
      $set = Settings::getInstance();
      $this->language = $this->sanitizeLanguage($set->get('language'));
      $this->destination = $destination;
      $this->loadTranslations($destination);
    } catch (Exception $e){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack=".$e->getTraceAsString();
      $this->logger-> write($messaggio);
    }
  }

  // Load the translations from the language file
  protected function loadTranslations($destination) { // $destination = 'php' or 'js'
    $this->logger = new LogPHP();
    try{
      $filePath = str_replace('\\','/', __DIR__ . "/../Resources/lang/{$this->language}.php");
      //echo $resolvedPath = realpath($filePath);
      if (file_exists($filePath) && is_readable($filePath)) {
        $translations = include $filePath;
        $this->translations = $translations[$destination]; // $destination = 'php' or 'js'
      } else {
        // Log the error
        $this->logger->write("Error: filePath: $filePath Translation file not found or not readable for language [{$this->language}] in [" . basename(__FILE__, '.php') . "].[" . __FUNCTION__ . "]");
        // Handle the error, e.g., set a default language
        $this->translations = [];
      }
    } catch (Exception $e){
      $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " Messaggio=".$e->getMessage();
      $messaggio .= " Stack=".$e->getTraceAsString();
      $this->logger-> write($messaggio);
    }
  }

  public function translate($key) {
    return $this->translations[$key] ?? $key;
  }
  
  public function getTranslations() {
    return $this->translations;
  }

  // Set the language and load the translations
  public function setLanguage($language) {
    $this->language = $this->sanitizeLanguage($language);
    $this->loadTranslations($this->destination);
  }

  public function getLanguage() {
    return $this->language;
  }

  // Sanitize the language to prevent injection attacks
  protected function sanitizeLanguage($language) {
    $allowedLanguages = ['en', 'fr', 'de', 'it']; // Example of allowed languages
    return in_array($language, $allowedLanguages) ? $language : 'en';
  }
}