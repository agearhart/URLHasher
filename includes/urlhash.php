<?php
/**
* Hashes a long url into a short one for recovery later
* @param string $longurl The URL to be hashed
* @param bool $validate_url optional If true, cURL the given address 
									 to make sure it's valid before hashing
* @param int $length optional The length of hash to generate
* @return string The hashed URL
*/
function hash_url($long_url, $validate_url = false, $length = 8)
{
	require('includes/config.php');
	$hashed_url = '';
	$valid_url = false;
	
	$url_reg = '%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i';
	
	if(!empty($long_url) && preg_match($url_reg, $long_url))//There's nothing on which to do work
	{
		if($validate_url)
		{
			$ch = curl_init($long_url);
			
			$curl_options = array(
				CURLOPT_RETURNTRANSFER => true
				);
				
			curl_setopt_array($ch, $curl_options);
			
			$curl_response = curl_exec($ch);
			$curl_info = curl_getinfo($ch);
			
			curl_close($ch);
			
			if($curl_info['http_code'] != 200)
			{
				$hashed_url = '';
			}
			else
			{
				$valid_url = true;
			}
		}
		else
		{
			$valid_url = true;
		}
		
		if($valid_url)//okay, let's generate a shortened URL
		{
			$alpha_len = strlen($alphabet);

			for ($i=0; $i < $length; ++$i)
			{
					$rand = mt_rand (0, $alpha_len);
					$hashed_url .= substr($alphabet, $rand, 1);
			}
		}
	}
	
	return $hashed_url;
}
?>