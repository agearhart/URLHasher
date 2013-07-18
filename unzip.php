<?php
/**
* This web point takes the given URI as a hashed URL and redirects the user to the proper URL
*/
require_once('includes/config.php');
require_once('includes/http_response_code.php');
require_once('includes/URLHasherDB.php');

$hashed_url = ( isset($_REQUEST['hash']) ) ? $_REQUEST['hash'] : '';

$db_conn = new URLHasherDB($db_dsn, $db_user, $db_password, $debug);
$db_conn->connect();

$long_url = $db_conn->hash_exists($hashed_url);

if($long_url != '')
{
	$db_conn->incr_hash_used($hashed_url);
	
	header('Location: '.$long_url);
	die();
}
else
{
	http_response_code(404);
	echo 'URL not found!';
}

?>