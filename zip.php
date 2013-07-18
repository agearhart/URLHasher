<?php
/**
* This web point accepts a GET paramter called long_url.  
* The database is then queried with this URL to see if it has been hashed before.
*		If it has been hashed before return the hash
*		Else generate a few hashes of it and try to insert one of the hashes into the DB
* The hashed URL is then returned to the client for later use with the unzip web point
*/
require_once('includes/config.php');
require_once('includes/http_response_code.php');
require_once('includes/URLHasherDB.php');
require_once('includes/urlhash.php');

$long_url = ( isset($_POST['long_url']) ) ? $_POST['long_url'] : '';

if($long_url != '')
{
	$long_url = urldecode($long_url);
	
	$db_conn = new URLHasherDB($db_dsn, $db_user, $db_password, $debug);
	$db_conn->connect();
	$hashed_url = $db_conn->url_exists($long_url);
	
	if($hashed_url != '')
	{
		http_response_code(200);
		echo $hashed_url;
		
		$db_conn->incr_cache_savings($hashed_url);
	}
	else
	{
		$result = false;
		$hashed_urls = hash_url($long_url, 5, true);
		$hashed_urls_len = count($hashed_urls);
		
		for($i=0; $i < $hashed_urls_len; ++$i)
		{
			$save_hash_result = $db_conn->save_hash($long_url, $hashed_urls[$i]);
			
			if($save_hash_result != '')
			{
				$hashed_url = $hashed_urls[$i];
				$result = true;
				break;
			}
		}
		
		if($result)
		{
			http_response_code(200);
			echo $hashed_url;
		}
		else
		{
			http_response_code(500);
			echo 'Server error.  Please try again.';
		}
	}
}
else
{
	http_response_code(412);
	echo 'Required parameter long_url not found.';
}

?>