<?php
// LogPHP.php
namespace RMapp\System;
use RMapp\Config\Settings;
use RMapp\System\Connection;
use Exception;
/*
example of use
try{

} catch (Exception $e){
	$error = new LogPHP;
	$messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
	$messaggio .= " Messaggio=".$e->getMessage();
	$messaggio .= " Stack=".$e->getTraceAsString();
	$error-> write($messaggio);
}
*/
class LogPHP {
	private $set;
	private $table = "";
	private $filelocation = "";
	function __construct() {
		$this->set = Settings::getInstance();
		$this->table = $this->set->get('TABLE_PREFIX') . $this->set->get('ERROR_TABLE');
		$this->filelocation = $this->set->get('LOG_FILE_NAME');
	}
	public function write($message){
		try{
			$ip_origin=$_SERVER['REMOTE_ADDR'];
			$query  = "INSERT INTO " . $this->table . " (`descrizione`, `ip_address`) ";
			$query .= "VALUES (?,'" . $ip_origin . "')";
			$var = substr($message,0,3000);
			$conn = new Connection;
			$db = $conn->connectDBExtra($this->set->get('PP'));
				
			if($stmt = mysqli_prepare($db, $query)){
				mysqli_stmt_bind_param($stmt, "s", $var);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				$stmt=null;
			} else {
				$errorNew = new LogPHP;
				$messaggio  = "Prepare failed: (" . mysqli_errno($db) . ") " . mysqli_error($db);
				$messaggio .= " in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
				$messaggio .= " query=".$query;
				$messaggio .= " var=". $var;
				$this->writeTxt($messaggio);
			}
		} catch (Exception $e){
			$error = new LogPHP;
			$messaggio  = "\t". "Caught exception=".$e->getMessage() . "\r\n";
			$messaggio .= "\t". "Query=".$query . "\r\n";
			$messaggio .= "\t". "Stack=".$e->getTraceAsString() . "\r\n";
			$error-> writeTxt($messaggio);
		}
	}
	public function writeTxt($message){
		try{
			$timestamp = time();
			$file = basename($_SERVER["SCRIPT_FILENAME"], '.php');
			$fp = fopen($this->filelocation, 'a');//opens file in append mode
			if ($fp) {
				$string = date("d-m-Y H:i:s",$timestamp) . "[". $file."]" . PHP_EOL;
				$string .= "\t".$message.":" . PHP_EOL;
				fwrite($fp,  $string);
				fclose($fp);
			} else {
				throw new Exception("Unable to open log file: " . $this->filelocation);
			}
		} catch (Exception $e){
			$error = new LogPHP;
			$messaggio  = "\t". "Caught exception=".$e->getMessage() . "\r\n";
			$messaggio .= "\t". "Stack=".$e->getTraceAsString() . "\r\n";
			$error-> write($messaggio);
		}
	}
}
?>