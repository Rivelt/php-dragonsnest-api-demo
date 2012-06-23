<?php

# Demo for Dragon's Nest OAuth

define('CLIENT_ID', 'c44e396fb98e8e7f6555911fd828fa43');
define('CLIENT_SECRET', '0d7a0182b5297ae66d9f4b62d5bff957');


function sendQuery($query)
{
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, $query);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'OAuth bot');
	$response = curl_exec($curl_handle);
	curl_close($curl_handle);
	$obj = json_decode($response);
	if($response and !$obj)
	{
		echo $response;
		exit;
	}
	return $obj;
}

if(empty($_COOKIE['uid']) and empty($_GET['code']))
{
	$redirect_uri = urlencode($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
	header('Location: http://dragons-nest.ru/forum/oauth.php?query=auth&redirect_uri='.$redirect_uri.'&client_id='.CLIENT_ID);
}
elseif(!empty($_GET['code']))
{
var_dump(sendQuery('http://dragons-nest.ru/forum/oauth.php?query=access_token&code='.$_GET['code'].'&client_id='.CLIENT_ID.'&client_secret='.CLIENT_SECRET));
}
else
{
	
}



