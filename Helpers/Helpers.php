<?php
// Helpers.php
namespace RMapp\Helpers;
use RMapp\System\LogPHP;
use Exception;

class Helpers{
  // Variable to store the current caller
  private static $callStack = [];
  
  // Function that verifies if the integer is a positive number as id or similar
  public static function validatePositiveInt($positiveint) : bool {
    $error = new LogPHP;
    try {
      if(!is_numeric($positiveint)) return false;
      if($positiveint < 0) return false;
      if((int)$positiveint != $positiveint) return false;
      return true;
    } catch (Exception $e) {
      $messaggio  = " Errore $e - ";
      $messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
      $messaggio .= " positiveint = $positiveint";
      $error-> write($messaggio);
    }
  }

  // Function that verifies if the email is valid
  public static function validateEmail($email) : bool {
    $email = trim($email);
    if(empty($email)) return false;
    if(strlen($email) > 255) return false;
    if((string)$email != $email) return false;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    return true;
  }

  // Function to validate correct hour format '00:00:00'
  public static function validateHours($hour){
    return preg_match('/^([0-1]?[0-9]|2[0-3]):([0-5]?[0-9]):([0-5]?[0-9])$/', $hour);
  } 

  // Start stack generation functions
  // Return the line of call 
  public static function getLine() {
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $line = $trace[0]['line'] ?? '[unknown]'; // Riga della chiamata
    return "(line $line)";
  }

  // Update the current caller with class and function 
  public static function setCaller(string $class, string $function, string $file): void {
    $fileWithoutExt = pathinfo($file, PATHINFO_FILENAME);
    $shortClass = basename(str_replace('\\', '/', $class));
    self::$callStack[] = "[File:$fileWithoutExt].[Cls:$shortClass].[Fun:$function]";
  }

  // Retrieve the last recorded caller.
  public static function getLastCaller(): string {
    return end(self::$callStack) ?: '[Unknown Caller]';
  }

  // Retrieve the entire call stack.
  public static function getCallStack(): string {
    return implode(' -> ', self::$callStack);
  }
  // End stack generation functions

}