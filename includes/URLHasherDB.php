<?php
class URLHasherDB
{
	private $dsn='';
	private $user='';
	private $password='';
	private $debug=false;
	
	private $db_conn = null;
	
	public function URLHasherDB($dsn, $user, $password, $debug = false)
	{
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
		$this->debug = $debug;
	}
	
	/**
	* Attempts to connect to the database given by the connection string in the config file
	*/
	public function connect()
	{
		try
		{
			$pdo_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
			$this->db_conn = new PDO($this->dsn, $this->user, $this->password, $pdo_options); 
		}
		catch(PDOException $e)
		{
			if($this->debug)
			{
				echo 'Database connection failed.';
				echo $e->getMessage();
			}
			
			$this->db_conn = null;
			http_response_code(500);
		}
	}

	/**
	* Given a long URL check the database to see if it has already been hashed
	* @param string $long_url The URL given to be hashed
	* @return string If the URL has previously been hashed, the hash will be returned, otherwise an empty string
	*/
	public function url_exists($long_url)
	{
		$hashed_url = '';
		
		if($long_url != '' && $this->db_conn != null)
		{
			$sql = 'SELECT `hash` FROM `hashes` WHERE `url` = ?';
			
			$query = $this->db_conn->prepare($sql);
	 
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
	* @param string $long_url The URL given to be hashed
	* @return string If the URL has previously been hashed, the hash will be returned, otherwise an empty string
	*/
	public function hash_exists($hashed_url)
	{
		$long_url = '';
		
		if($hashed_url != '' && $this->db_conn != null)
		{
			$sql = 'SELECT `url` FROM `hashes` WHERE `hash` = ?';
			
			$query = $this->db_conn->prepare($sql);
	 
			$query->bindValue(1, $hashed_url);
			
			$query->execute();

			while( $row = $query->fetch(PDO::FETCH_NUM) )
			{
				$long_url = $row[0];
			}
		}
		
		return $long_url;
	}

	/**
	* Saves the long URL as a map to the hashed URL
	* @param string $long_url the full URL
	* @param string $hashed_url The generated hash of the URL
	* @return bool True if saved, false otherwise
	*/
	public function save_hash($long_url, $hashed_url)
	{
		if($this->db_conn != null && $long_url != '' && $hashed_url != '')
		{
			//check to make sure that this hashcode doesn't already exist
			$attempted_hash = $this->hash_exists($hashed_url);
			
			if( !empty($attempted_hash) )
			{
				return false;
			}
			
			$this->db_conn->beginTransaction();
			$sql = 'INSERT INTO `hashes` (`url`,`hash`,`date_inserted`) VALUES (?,?,UTC_TIMESTAMP())';
			
			$insert = $this->db_conn->prepare($sql);
			
			$insert->bindValue(1, $long_url, PDO::PARAM_STR);
			$insert->bindValue(2, $hashed_url, PDO::PARAM_STR);
			
			$result = $insert->execute();
			
			$this->db_conn->commit();
				
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* For metrics purposes log how many times a given long URL has already been hashed
	* @param string $hashed_url The URL hash that has been previously cached
	*/
	public function incr_cache_savings($hashed_url)
	{
		if($hashed_url != '' && $this->db_conn != null)
		{
			$this->db_conn->beginTransaction();
			$sql = 'UPDATE `hashes` SET `cache_savings` = `cache_savings` + 1 WHERE `hash` = ?';
			
			$update = $this->db_conn->prepare($sql);
			
			$update->bindValue(1, $hashed_url);
			
			$result = $update->execute();
			$this->db_conn->commit();
			
			return $result;
		}
	}
	
	/**
	* For metrics purposes log how many times this hashed URL has been unzipped
	* @param string $hashed_url The URL hash that has just been unzipped
	*/
	public function incr_hash_used($hashed_url)
	{
		if($hashed_url != '' && $this->db_conn != null)
		{
			$this->db_conn->beginTransaction();
			$sql = 'UPDATE `hashes` SET `used_count` = `used_count` + 1 WHERE `hash` = ?';
			
			$update = $this->db_conn->prepare($sql);
			
			$update->bindValue(1, $hashed_url);
			$this->db_conn->commit();
			
			return $update->execute();
		}
	}
}


?>