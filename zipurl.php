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
require_once('includes/db_functions.php');
require_once('includes/urlhash.php');

$long_url = ( isset($_REQUEST['long_url']) ) ? $_REQUEST['long_url'] : '';

if($long_url != '')
{
	$db_conn = db_connect($db_dsn, $db_user, $db_password, $debug);
	$hashed_url = db_hash_exists($db_conn, $long_url);
	
	if($hashed_url != '')
	{
		http_response_code(200);
		echo $hashed_url;
	}
	else
	{
		$hashed_url = hash_url($long_url, true);
		$result = db_save_hash($db_conn, $long_url, $hashed_url);
		
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