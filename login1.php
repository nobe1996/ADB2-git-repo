
<?php
session_start();
include_once('dbconnect.php');
if (isset($_GET['logout'])){
	unset($_SESSION['login']);
	unset($_SESSION['login-name']);
	unset($_SESSION['id']);
}
if (!isset($_SESSION['login'])){
	$_SESSION['login'] = false;
}
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
				$_SESSION['login_name'] = $row['felhasz'];
				$_SESSION['id'] = $row['id'];
				$_POST = array();
				header("Location: ./index.php");
			}
		}
	}
	$_POST = array();
}
?>
<div class="login">
	<?php if ($message != ""){?>
		<div class="error-message">
			<p><?php echo $message;?></p>
		</div>
	<?}?>
	<form class="login-form" method="post" action="./index.php">
		<label>Felhasználónév:</label>
		<input type="text" name="username" value="" placeholder="Adja meg felhasználónevét..." required/>
		<label>Jelszó:</label>
		<input type="password" name="password" value="" placeholder="Adja meg jelszavát..." required/>
		<p>
			<a href="./index.php?signup">Regisztráció</a>
			<a href="./index.php?forgotpass">Elfelejtett jelszó</a>
		</p>
		<input type="submit" name="send" value="Bejelentkezés"/>
	</form>
</div>