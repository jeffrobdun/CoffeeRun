<?php
require('init.php');
?>
<ul id="nav-bar" >
    <li><a href="index.php">Home</a></li>
    <li>|</li>
    <?php
    if(!$_COOKIE['username']){?>
        <li><a id="login">Login</a></li>
    <?php
	}else{?>
        <li><a href="profile.php">Profile</a></li>
	<?php
	}?>
    
    <?php
    if(isset($_COOKIE['username'])){?>
        <li style="float:right;padding-right:10px;"><a href="logout.php">Logout</a></li>
    <?php
    }else{?>
        <li style="float:right;padding-right:10px;"><a href="register.php">Register</a></li>
    <?php
    }?>
</ul>
	
<div id="login-form" style="display:none" style="position:absolute;">
<table>
<form action="processLogin.php" method="POST">
	<tr>
		<td>
			<label>Username</label>
		</td>
		<td>
			<input type="text" name="username" id="username" />
		</td>
	</tr>
	<tr>
		<td>
			<label>Password</label>
		</td>
		<td>
			<input type="password" name="password" id="password" />
	</tr>
	<tr>
		<td>
			<input type="submit" value="Login" />
		</td>
		<td>
			<input type="button" id="cancel" value="Cancel" />
		</td>
	</tr>
</form>
</table>
</div>
	
<script type="text/javascript">
    //        var myString = document.getElementById('date-list').innerHTML;

    $("#login").click(function(){
    	
    	if($("#login-form").css('display') == 'none'){
			$("#login-form").slideDown();
    	}else{
    		$("#login-form").slideUp();
    	}
    });
    
    $("#cancel").click(function(){
    	$("#login-form").slideUp();
    });
   
</script>