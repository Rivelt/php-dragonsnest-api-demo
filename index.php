<?php

# Demo for Dragon's Nest OAuth

define('CLIENT_ID', 'c44e396fb98e8e7f6555911fd828fa43');
define('CLIENT_SECRET', '0d7a0182b5297ae66d9f4b62d5bff957');

session_start();

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

if(!empty($_GET['login']))
{
	$redirect_uri = urlencode('http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
	header('Location: http://dragons-nest.ru/forum/oauth.php?query=auth&redirect_uri='.$redirect_uri.'&client_id='.CLIENT_ID);
	exit;
}
elseif(!empty($_GET['code']))
{
	$result = sendQuery('http://dragons-nest.ru/forum/oauth.php?query=access_token&code='.$_GET['code'].'&client_id='.CLIENT_ID.'&client_secret='.CLIENT_SECRET);
	$data = sendQuery('http://dragons-nest.ru/forum/oauth.php?query=whoami&access_token='.$result->access_token);
	setcookie('data', serialize($data), time()+3600*24*365*10);
	setcookie('uid', $data->uid, time()+3600*24*365*10);
	header('Location: /');
	exit;
}
elseif(!empty($_GET['logout']))
{
	if(@$_GET['sid']!=md5('salt'.session_id()))
	{
		echo 'What you want without pretty sid?';
		exit;
	}
	setcookie('data', '');
	setcookie('uid', '');
	header('Location: /');
	exit;
}

$data = @unserialize(@$_COOKIE['data']);
?><html>
	<head>
		<title>Dragon's Nest demo</title>
	</head>
	<body>
		<?php if($data): ?>
		Username: <?=$data->name;?><br>
		ID: <?= $data->uid;?><br >
		Avatar:<br> <img src="<?=$data->avatar_src?>" /><br>
		<a href="?logout=1&sid=<?=md5('salt'.session_id())?>">Logout</a>
		<?php else: ?>
		<a href="?login=1">Login</a>
		<?php endif;?>
	</body>
</html>



