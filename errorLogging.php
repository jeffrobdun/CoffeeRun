<?php
$pwd = shell_exec("pwd");
// /var/log/httpd/error_log
// $file = file("../../log/httpd/error_log");
$tail = shell_exec("tail ../../log/httpd/error_log");
printf('<pre>%s</pre>',print_r($tail,true));
// $endOfLog = array();
// for($i=0;$i<count($file);$i++){
// 	$endOfLog[] = $file[$i];
// }
?>

