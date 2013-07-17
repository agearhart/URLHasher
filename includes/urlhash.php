<?php
/**
* Hashes a long url into a short one for recovery later
* @param string $longurl The URL to be hashed
* @param int $quantity The number of hashes to generate
* @param bool $validate_url optional If true, cURL the given address 
									 to make sure it's valid before hashing
* @param int $length optional The length of hash to generate
* @return string The hashed URL
*/
function hash_url($long_url, $quantity = 1, $validate_url = false, $length = 8)
{
	$alphabet='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$hashed_urls = array();
	$valid_url = false;
	
	$url_reg = '%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i';
	
	if(!empty($long_url) && preg_match($url_reg, $long_url))//There's nothing on which to do work
	{
		if($validate_url)
		{
			$ch = curl_init($long_url);
			
			$curl_options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT_MS => 500
				);
				
			curl_setopt_array($ch, $curl_options);
			
			
			$curl_response = curl_exec($ch);
			
			$curl_info = curl_getinfo($ch);
			
			curl_close($ch);
			
			if($curl_info['http_code'] == 200)
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

			for($j=0; $j < $quantity; ++$j)
			{
				$this_hash = '';
				for ($i=0; $i < $length; ++$i)
				{
						$rand = mt_rand (0, $alpha_len);
						$this_hash .= substr($alphabet, $rand, 1);
				}
				
				$hashed_urls[] = $this_hash;
			}
		}
	}
	
	return $hashed_urls;
}
?>