<?php
include_once('dbconnect.php');
$message = "";
if(isset($_POST['submit'])){
	/*$username = $_SESSION["login_name"];
	
	$stid = oci_parse($conn, "SELECT FELHASZNALONEV, JELSZO FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $username ."'");
	if (!$stid) {
		$e = oci_error($conn);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$r = oci_execute($stid);
	if (!$r) {
		$e = oci_error($stid1);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$stmt= oci_parse($conn, "SELECT COUNT(FELHASZNALONEV) AS NUMBER_OF_ROWS FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $username ."'");
	oci_define_by_name($stmt, 'NUMBER_OF_ROWS', $number_of_rows);
	oci_execute($stmt);
	oci_fetch($stmt);
	if ($username != "" && $jelszo != "") {
		if ($number_of_rows == '0'){
			$message = "A felhasználó nem létezik!";
		} else {
			$row = oci_fetch_assoc($stid);		
			if ($jelszo != $row['JELSZO']){
				$message = "A megadott jelszó nem helyes!";
			} else {
				$_SESSION['login'] = true;
				$_SESSION['login_name'] = $row['FELHASZNALONEV'];
				$_POST = array();
				header("Location: indexpage.php");
			}
		}
	}
	$_POST = array();*/
	
	
	
			$stmt= oci_parse($conn, "SELECT COUNT(URL) AS NUMBER_OF_PICTURES FROM KEPEK");
			oci_define_by_name($stmt, 'NUMBER_OF_PICTURES', $number_of_pictures);
			oci_execute($stmt);
			oci_fetch($stmt);
			
			
			$temp = explode(".", $_FILES["fileToUpload"]["name"]);
			$newfilename = ($number_of_pictures+1). '.' . end($temp);
			$target_dir = "images/";
			
			$target_file = $target_dir . $newfilename;
			
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					echo "A fájl kép - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "A fájl nem kép.";
					$uploadOk = 0;
				}
			}

			if (file_exists($target_file)) {
				echo "Már létezik ilyen fájl!";
				$uploadOk = 0;
			}

			if ($_FILES["fileToUpload"]["size"] > 50000000) {
				echo "A fájl mérete túl nagy.";
				$uploadOk = 0;
			}

			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Elnézést, csak JPG, JPEG, PNG & GIF fájl típusok engedélyezettek.";
				$uploadOk = 0;
			}

			if ($uploadOk == 0) {
				echo "A fájl nem lett feltöltve.";

			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "A ". basename( $_FILES["fileToUpload"]["name"]). " nevű fájl feltöltve.";
					$filename = basename( $_FILES["fileToUpload"]["name"]);
				echo 'Mérete ' . round(filesize($filename)/1024) . ' kilobájt.';
				}
			}
		
}
?>

	<form action="upload.php" method="post" enctype="multipart/form-data" align="center">
            Kép feltöltése: <input type="file" name="fileToUpload">
			Kép készítésének helye:
			Kategória:
            <input type="submit" value="Feltoltes" name="submit">
    </form>