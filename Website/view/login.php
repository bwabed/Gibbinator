<!-- Die Zeile mit dem Login falls der Benutzer nicht eingelogt ist sonst der Abmelde Button -->
<div id="header">
	<h1 id="title">Pics are love</h1>
<?php if($isLogdin == false){ ?>
 <div id="login">
 	<form action="index.php" method="post"  onsubmit="return validateLogin();">
 		<input type="hidden" name="cont" value="Login" />
		<input type="hidden" name="action" value="login">

		<label for="emaillog">Email</label>
		<input type="email" name="email" id="emaillog">
		<label for="passwdlog">Passwort</label>
		<input type="password" name="passwd" id="passwdlog">
		<input type="submit" value="Login" />  
	</form>
	<div>
		<p class="error"><?php echo $loginError ?></p>
	</div>
	</div>

<?php }else{ ?>

 <div id="login">
 	<p class="username"><?php echo $userName ?></p>
 	<form method="post" action="index.php">
 		<input type="hidden" name="cont" value="Login" />
		<input type="hidden" name="action" value="logout">
		<input type="submit" value="Abmelden" />
 	</form>
 </div>
<?php } ?>


</div>