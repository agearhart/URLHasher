<?php
/**
* This web point takes the given URI as a hashed URL and redirects the user to the proper URL
*/
require_once('includes/config.php');
require_once('includes/http_response_code.php');
require_once('includes/db_functions.php');

$hashed_url = ( isset($_REQUEST['hash']) ) ? $_REQUEST['hash'] : '';

if($debug)
{
	echo 'REQUEST_URI = '.$hashed_url."<br/>";
}

$db_conn = db_connect($db_dsn, $db_user, $db_password, $debug);

$long_url = db_hash_exists($db_conn, $hashed_url);

if($long_url != '')
{
	http_redirect($long_url);
}
else
{
	http_response_code(404);
	echo 'URL not found!';
}

?>