<?php

$urls = array(
	"http://www.google.com",//should be fine
	"www.google.com",//should fail
	"http://www.reddit.com/r/motorcycles/comments/1if3ts/rolling_art/",//should pass
	"\';DROP TABLE `urls`;--", //Little Bobby Tables http://xkcd.com/327/ better fail
	"http://xkcd.com/327/",//should be okay
	"https://mwtactics.com/",//should be okay
	"I must not fear.
Fear is the mind-killer.
Fear is the little-death that brings total obliteration.
I will face my fear.
I will permit it to pass over me and through me.
And when it has gone past I will turn the inner eye to see its path.
Where the fear has gone there will be nothing.
Only I will remain.",//should fail
	"",//should fail
	"http://www.pressenterpriseonline.com/categories/comics", //should be okay
	"http://toucharcade.com/2013/07/17/futuridium-ep-is-a-space-shooter-with-awesome-music-and-visuals-thats-launching-tonight/"//really long URL that should be okay
	);

//Get the hashes for the URLs given
$zip_url='http://ajgear.net/zipapi/zip';
$hashes = array();

foreach($urls as $url)
{
	$post_data = array('long_url'=>urlencode($url));
	
	$ch = curl_init($zip_url);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	
	$result = curl_exec($ch);
	$curl_info = curl_getinfo($ch);
			
	curl_close($ch);
	
	$hashes[] = array('URL'=>$url,'code'=>$curl_info['http_code'], 'hash'=>$result);
}

//See if the hashes translated
$unzip_url = 'http://ajgear.net/zipapi/unzip/';
$unzip_results = array();

foreach($hashes as $hash)
{
	$ch = curl_init($unzip_url.$hash['hash']);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	$curl_info = curl_getinfo($ch);
			
	curl_close($ch);
	
	$unzip_results[] = $curl_info['http_code'];
}

print_r($hashes);
echo '<br/><br/>';
print_r($unzip_results);

?>