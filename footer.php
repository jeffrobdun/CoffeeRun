</div>
<ul id="footer" >
	<li><a href="home.php">Home</a></li>
    <li>|</li>
    <?php
    if(!$_COOKIE['username']){?>
        <li><a id="login">Login</a></li>
    <?php
	}else{?>
        <li><a href="profile.php">Profile</a></li>
	<?php
	}?>
    <li>|</li>
    <li><a href="lists.php">Lists</a></li>
    <li>|</li>    
    <li><a href="achievements.php">Achievements</a></li>
    
</ul>
</body>
</html>
<script type="text/javascript">
	function setFooterHeight(){
		if($(document).height() <= $(window).height()){
			$("#footer").css("top",$(window).height() - 35);
		}else{ 
			// var multiplier = Math.ceil($(document).height() / $(window).height());
			// var height = $(window).height() * multiplier;
			// $("#footer").css("top",height - 35);
			$("#footer").css("top",$(window).height());			
		}
	}
</script>