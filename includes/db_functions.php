<?php
/**
* Attempts to connect to the database given by the connection string in the config file
* @param string $dsn PDO connection string to DB
* @param string $user DB username
* @param string $password DB username's password
* @param bool $debug If in debug mode, output is more verbose
* @return PDO
*/
function db_connect($dsn, $user, $password, $debug = false)
{
	try
	{
		$pdo_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
		$db = new PDO($dsn, $user, $password, $pdo_options); 
		return $db;
	}
	catch(PDOException $e)
	{
		if($debug)
		{
			echo 'Database connection failed.';
			echo $e->getMessage();
		}
		
		http_response_code(500);
	}
}

/**
* Given a long URL check the database to see if it has already been hashed
* @param PDO $db_conn An active PDO connection to a database
* @param string $long_url The URL given to be hashed
* @return string If the URL has previously been hashed, the hash will be returned, otherwise an empty string
*/
function db_url_exists($db_conn, $long_url)
{
	$hashed_url = '';
	
	if($long_url != '' && $db_conn != null)
	{
		$sql = 'SELECT hash FROM `hashes` WHERE `url` = ?';
		
		$query = $db_conn->prepare($sql);
 
		$query->bindValue(1, $long_url);
		
		$query->execute();
		
		$result = $query->fetch();
		
		if(count($result) > 0)
		{
			$hashed_url = $result[0];
		}
	}
	
	return $hashed_url;
}

/**
* Given a long URL check the database to see if it has already been hashed
* @param PDO $db_conn An active PDO connection to a database
* @param string $long_url The URL given to be hashed
* @return string If the URL has previously been hashed, the hash will be returned, otherwise an empty string
*/
function db_hash_exists($db_conn, $long_url)
{
	$hashed_url = '';
	
	if($long_url != '' && $db_conn != null)
	{
		$sql = 'SELECT hash FROM `hashes` WHERE `hash` = ?';
		
		$query = $db_conn->prepare($sql);
 
		$query->bindValue(1, $long_url);
		
		$query->execute();
		
		$result = $query->fetch();
		
		if(count($result) > 0)
		{
			$hashed_url = $result[0];
		}
	}
	
	return $hashed_url;
}

/**
* Saves the long URL as a map to the hashed URL
* @param PDO $db_conn An active PDO connection to a database
* @param string $long_url the full URL
* @param string $hashed_url The generated hash of the URL
* @return bool True if saved, false otherwise
*/
function db_save_hash($db_conn, $long_url, $hashed_url)
{
	if($db_conn != null && $long_url != '' && $hashed_url != '')
	{
		$sql = 'INSERT INTO `hashes` (`url`,`hash`,`date_inserted`) VALUES (?,?,UTC_TIMESTAMP)';
		
		$insert = $db_conn->prepare($sql);
		
		$insert->bindValue(1, $long_url);
		$insert->bindValue(2, $hashed_url);
		
		return $insert->execute();
	}
	else
	{
		return false;
	}
}

?>