<!-- <link href='http://fonts.googleapis.com/css?family=Share+Tech' rel='stylesheet' type='text/css'> -->
<link href="/css/main.css" rel="stylesheet" type="text/css"/>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php
$page = substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/") + 1);
if(strpos($page, "?") > 0){
	$page = substr($page, 0, strpos($page, "?"));
}

if(empty($page)){
	$page = 'index.php';
}

$handle = fopen("titles.csv", "r");
while($csv = fgetcsv($handle)){
	if($csv[0] == $page){
		print $csv[1];
		break;
	}
} 

if(!empty($_COOKIE['username'])){
	$user = new user($_COOKIE['username']);
	
}
?>
</title>
</head>
<body onload="setFooterHeight();">
<?php
include("navBar.php");
include("messageDisplay.php");
 ?>
<div class="container-fluid">
  

