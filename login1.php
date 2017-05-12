<?php
$message = "";
if(isset($_POST['send'])){
	$jelszo = htmlspecialchars($_POST['password']);
	$username = htmlspecialchars($_POST['username']);
	
	$stid = oci_parse($conn, "SELECT FELHASZNALONEV, JELSZO FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $usr ."'");

	if (!$stid) {
		$e = oci_error($conn);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute($stid);

	if (!$r) {
		$e = oci_error($stid1);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	if ($username != "" && $jelszo != "") {
		if (oci_num_rows($stid) == 0){
			$message = "A felhasználó nem létezik!";
		} else {
			$row = oci_fetch_assoc($stid);		
			if ($jelszo != $row['JELSZO']){
				$message = "A megadott jelszó nem helyes!";
			} else {
				$_SESSION['login'] = true;
				$_SESSION['login_name'] = $row['FELHASZNALONEV'];
				$_POST = array();
				header("Location: ./indexpage.php");
			}
		}
	}
	$_POST = array();
}
?>
<!--<div id="login" class="login">-->
	<?php if ($message != ""){?>
			<p><?php echo $message;?></p>
	<?}?>
	<form method="post" action="indexpage.php">
		<input type="text" name="username" value="" placeholder="Adja meg felhasználónevét...">
		<input type="password" name="password" value="" placeholder="Adja meg jelszavát...">
		<input type="submit" name="send" value="Bejelentkezés"/>
	</form>
	<a href="signup.php">Regisztráció</a>
<!--</div>-->