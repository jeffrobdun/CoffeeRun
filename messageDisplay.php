<?php

if(count($_SESSION['error']) > 0){
	print '<div id="error" class="round1">';
	for($i=0;$i<count($_SESSION['error']);$i++){
		print $_SESSION['error'][$i] . '<br/>';
	}
	print '</div><br/>';
}elseif(count($_SESSION['message']) > 0){
	print '<div id="message" class="round1">';
	for($i=0;$i<count($_SESSION['message']);$i++){
		print $_SESSION['message'][$i] . '<br/>';
	}
	print '</div><br/>';
}

unset($_SESSION['error']);
unset($_SESSION['message']);

if(!is_null($_SESSION['achievement'])){ 
$achievement = new achievement($_SESSION['achievement']);?>
<script type="text/javascript">
	alert(<?= $achievement->get('name'); ?>);
</script>
<?php
}?>