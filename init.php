<?php

$classesPath = "/var/www/html/classes";
$classes = scandir($classesPath);

$backtrace = debug_backtrace();
$parentFiles=array();
foreach ($backtrace as $backtraceFile) {
	$parentFiles[] = $backtraceFile["file"];
}

foreach ($classes as $class) {
	if(preg_match('/^.+\.php$/i', $class)){
		$includedFiles = get_included_files();
		if(!in_array($classesPath . "/" . $class, $includedFiles) && !in_array($classesPath . "/" . $class, $parentFiles))
			include($classesPath . "/" . $class);

	}
}
$database = new database();
global $database;
session_start(); 
?>
<!-- <script src="jquery.js" type="text/javascript"></script>
<script src="js/functions.js" type="text/javascript"></script> -->





