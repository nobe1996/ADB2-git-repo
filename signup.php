<?php
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<link href="main.css" rel="stylesheet" type="text/css" />
<title>Test</title>
</head>
<body>
<?php
include_once('dbconnect.php');
$message = "";
if (isset($_POST['signup'])){
	if (htmlspecialchars($_POST["username"]) == "" ||
			htmlspecialchars($_POST["nev"]) == "" ||
			htmlspecialchars($_POST["password"]) == "" ||
			htmlspecialchars($_POST["passwordagain"]) == "" ||
			htmlspecialchars($_POST["groups"]) == "" ||
			htmlspecialchars($_POST["hometown"]) == ""){
		$message = "A *-al jelölt mezők kitöltése kötelező!";
	} else {
		
			$username = htmlspecialchars($_POST['username']);
			$stmt= oci_parse($conn, "SELECT COUNT(FELHASZNALONEV) AS NUMBER_OF_ROWS FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $username ."'");
			oci_define_by_name($stmt, 'NUMBER_OF_ROWS', $number_of_rows);
			oci_execute($stmt);
			oci_fetch($stmt);
			if ($number_of_rows == '1'){
				$message = "A felhasználó már létezik!";
			}else if(strlen(htmlspecialchars($_POST['password'])) < 8){
				$message = "A jelszónak min 8 karakternek kell lennie!";
			} else if((htmlspecialchars($_POST['password']) != htmlspecialchars($_POST['passwordagain']))){
				$message = "A jelszavaknak meg kell egyezniük!";
			} else {
				$values = "'".htmlspecialchars($_POST["username"])."','".htmlspecialchars($_POST["password"])."','".htmlspecialchars($_POST["nev"])."','".htmlspecialchars($_POST["hometown"])."','".htmlspecialchars($_POST["groups"])."'";
				
				$stid = oci_parse($conn, 'INSERT INTO FELHASZNALOK (FELHASZNALONEV, JELSZO, NEV, HELY_ID, CS_NEV) VALUES ('.$values.')');
				oci_execute($stid);
				
				$_SESSION['login'] = true;
				$_SESSION['login_name']= htmlspecialchars($_POST['username']);
				header("Location: ./index.php");
			}
	}
}
$_POST = array();
?>
<div class="login">
	<?php if ($message != ""){?>
			<p><?php echo $message;?></p>
	<?php 
	}
	 ?>
	<form method="post" action="signup.php">
		Felhasználónév:*<input type="text" name="username" value="" maxlength="20"><br>
		Név:*<input type="text" name="nev" value="" maxlength="20"><br>
		Jelszó:* (legalább 8 karakter)<input type="password" name="password" value="" maxlength="20"><br>
		Jelszó mégegyszer:*<input type="password" name="passwordagain" value=""><br>
		Csoport:* 
		<select name="groups">
			<option value="" disabled selected>Válassz csoportot.</option>
				<?php
					$stid = oci_parse($conn, "SELECT CS_NEV FROM CSOPORTOK");
					oci_execute($stid);

					while ($row = oci_fetch_assoc($stid)) { 
						echo '<option value="'. $row["CS_NEV"] . '">'.$row["CS_NEV"] .'</option>'; 
					} 
				?>
		</select><br>
		Lakhely:*
		<select name="hometown">
				<option value="" disabled selected>Válassz lakhelyet.</option>
				<?php
					$stid1 = oci_parse($conn, "SELECT HELY_ID, ORSZAG, MEGYE, TELEPULES FROM HELYEK");
					oci_execute($stid1);

					while ($row = oci_fetch_assoc($stid1)) { 
						echo '<option value="'. $row["HELY_ID"] . '">'.$row["ORSZAG"] .','.$row["MEGYE"] .','.$row["TELEPULES"] .'</option>'; 
					} 
				?>
		</select><br>
		<input type="submit" name="signup" value="Regisztráció">
	</form>
</div>
</body>
</html>