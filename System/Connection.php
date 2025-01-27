<?php
namespace RMapp\System;
use RMapp\Config\Settings;
use Exception;
use mysqli;

class Connection {
	private $set;
	function __construct() {
		$this->set = Settings::getInstance();
	}

	/* Example 
	 * $conn = new Connection();
	 * $db = $conn->connectDBExtra($param);
	*/
	/** Database Connection
	 * @param string $param
	 * @return mysqli
	 */
	function connectDBExtra(string $param) : mysqli {
		if ($param != $this->set->get('PP')){die;}
		$connerror = new LogPHP;
    $db =  mysqli_connect($this->set->get('DB_HOST'), $this->set->get('DB_USER'), $this->set->get('DB_PASS'), $this->set->get('DB_NAME'));
		if (!$db) {
			$connerror->writeTxt('Connect Error: ' . mysqli_connect_errno());
			die("Error: Connection nok");
		}
		return $db;
	}

	/* Example
	 * $conn = new Connection();
	 * $query = "SELECT * FROM `cassa_movimenti` WHERE 1";
	 * $resultquery = $conn->getDBQuery($param,$query);
	*/	
	/** Query database to open
	 * @param string $param
	 * @param string $query
	 * @return mysqli_result||false
	 */
	function getDBQuery(string $param,string $query) {
		$set = Settings::getInstance();
		if ($param!=$set->get('PP')){die;}
		try {
			$db = $this->connectDBExtra($param);
			$resultquery = mysqli_query($db,$query);
			if (!$resultquery) {
				$QueryLog = new LogPHP;
				$messaggio  = "\t". "La query è andata in errore\r\n";
				$messaggio .= "\t". "Query: " . htmlentities($query) . "\r\n";
				$messaggio .= "\t". "Errno: " . $db->errno . "\r\n";
				$messaggio .= "\t". "Error: " . $db->error . "\r\n";
				$QueryLog-> writeTxt($messaggio);
				return false;
			}
			mysqli_close($db);
		} catch (Exception $e) {
			$error = new LogPHP;
			$messaggio  = "Caught exception=".$e->getMessage();
			$messaggio .= " Query=".$query ;
			$error-> write($messaggio);
			return false;
		}
		return $resultquery;
	}

	/** Example
	 * $conn = new Connection;
	 * $db = $conn->connectDBExtra($param);
	 * $query = "SELECT * FROM `cassa_movimenti` WHERE 1";
	 * $resultquery = $conn->getDBQueryDBOpened($param,$query,$db);
	 * mysqli_close($db);	
	*/
	/** Query an already open database
	 * @param string $param
	 * @param string $query
	 * @return mysqli_result||false
	 */
	function getDBQueryDBOpened(string $param,string $query, mysqli $db) {
		$error = new LogPHP;
		$resultquery = null;
		$set = Settings::getInstance();
		if ($param!=$set->get('PP')){die;}
		try {
			$resultquery = mysqli_query($db, $query);
			if (!$resultquery) {
				$QueryLog = new LogPHP;
				$messaggio  = "\t". "La query è andata in errore\r\n";
				$messaggio .= "\t". "Query: " . htmlentities($query) . "\r\n";
				$messaggio .= "\t". "Err number: " . $db->errno . "\r\n";
				$messaggio .= "\t". "Error : " . $db->error . "\r\n";
				$QueryLog-> writeTxt($messaggio);
			}
		} catch (Exception $e) {
			$messaggio  = "Caught exception=".$e->getMessage();
			$messaggio .= " Query=".$query ;
			$error-> write($messaggio);
		}
		return $resultquery;
	}

	/** Example 
	 * $conn = new Connection;
	 * $db = $conn->connectDBExtra($param);
	 * $tableName = "cassa_movimenti";
	 * $resultquery = $conn->tableExist($param,$tableName);
	 * mysqli_close($db);	
	*/
	/** Check if table exist in DB opened
	 * @param string $param
	 * @param string $tableName
	 * @return bool
	 */
	function tableExist(string $param,string $tableName) : bool {
		$set = Settings::getInstance();
		if ($param!=$set->get('PP')){die;}
		try {
			$db = $this->connectDBExtra($param);
			$checkTableExist = mysqli_query($db,'select 1 from `'. $tableName . '` LIMIT 1');
			if ($checkTableExist !== FALSE){
				return true;
			} else {
				return false;
			}
			mysqli_close($db);
		} catch (Exception $e) {
			$error = new LogPHP;
			$messaggio  = "Caught exception=".$e->getMessage();
			$messaggio .= " tableName=".$tableName;
			$error-> write($messaggio);
		}
	}

	/** Example
	 * $conn = new Connection();
   * $db = $conn->connectDBExtra($param);
   * if($conn->manageTransaction($param, $db, 'start')){
   * 
   * 	$query1 = "INSERT INTO `tabella` (`colonna1`, `colonna2`) VALUES ('valore1', 'valore2')";
   * 	$result1 = $conn->getDBQueryDBOpened($param, $query1, $db);
   * 
   * 	$query2 = "UPDATE `tabella` SET `colonna1` = 'nuovo_valore' WHERE `id` = 1";
   * 	$result2 = $conn->getDBQueryDBOpened($param, $query2, $db);
   * 
   * 	if ($result1 && $result2) {
   * 			$conn->manageTransaction($param, $db, 'commit');
   * 	} else {
   * 			$conn->manageTransaction($param, $db, 'rollback');
   * 	}
   * }
   * mysqli_close($db);
	 */
	/** Manage transaction
	 * @param string $param
	 * @param mysqli $db Connection open
	 * @param string $action start|commit|rollback
	 * @return bool
	 */
	function manageTransaction(string $param, mysqli $db, string $action) : bool {
		if ($param != $this->set->get('PP')){die;}
		$retval = false;
		try {
				switch (strtolower($action)) {
						case 'start':
								mysqli_autocommit($db, false);
								$retval = mysqli_begin_transaction($db);
								return $retval;
						case 'commit':
								$retval = mysqli_commit($db);
								mysqli_autocommit($db, true);
								return $retval;
						case 'rollback':
								$retval = mysqli_rollback($db);
								mysqli_autocommit($db, true);
								return $retval;
						default:
							return $retval;
				}
		} catch (Exception $e) {
				$error = new LogPHP;
				$messaggio  = "Caught exception=".$e->getMessage();
				$messaggio .= " Action=".$action;
				$error->write($messaggio);
				return $retval;
		} finally {
			// Riabilita l'autocommit e chiudi la connessione
			mysqli_autocommit($db, TRUE);
		}
	}

}

?>