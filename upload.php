<?php
include_once('dbconnect.php');
$message = "";
if(isset($_POST['submit'])){
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
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$uploadOk = 1;
			} else {
				$uploadOk = 0;
			}
			if ($_FILES["fileToUpload"]["size"] > 50000000) {
				$uploadOk = 0;
			}
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$uploadOk = 0;
			}
			
			echo $uploadOk;
			echo $_POST['location'];
			echo $_POST['categories'];
			
			if (($uploadOk == 1) && ($_POST['location'] != '') && ($_POST['categories'] != '') ) {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$values = "'".$target_file."','".$_SESSION['login_name']."','".htmlspecialchars($_POST["location"])."','".htmlspecialchars($_POST["categories"])."'";
					echo "siker";
					$stid = oci_parse($conn, 'INSERT INTO KEPEK (URL, FELHASZNALONEV, HELY_ID, KAT_NEV) VALUES ('.$values.')');
					oci_execute($stid);
					$_POST = array();
					header("Location: indexpage.php");
				}
			}

			$_POST = array();
}	
?>


	<form action="upload.php" method="post" enctype="multipart/form-data" align="center">
            Kép feltöltése: <input type="file" name="fileToUpload"><br>
			Kép készítésének helye:
			<select name="location">
				<option value="" disabled selected>Válassz kép készítésének helyét.</option>
				<?php
					$stid1 = oci_parse($conn, "SELECT HELY_ID, ORSZAG, MEGYE, TELEPULES FROM HELYEK");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo '<option value="'. $row["HELY_ID"] . '">'.$row["ORSZAG"] .','.$row["MEGYE"] .','.$row["TELEPULES"] .'</option>'; 
					} 
				?>
			</select><br>
			Kategória:
			<select name="categories">
				<option value="" disabled selected>Válassz kategóriát.</option>
				<?php
					$stid1 = oci_parse($conn, "SELECT KAT_NEV FROM KATEGORIAK");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo '<option value="'. $row["KAT_ID"] . '">'.$row["KAT_NEV"] .'</option>'; 
					} 
				?>
			</select><br>
            <input type="submit" value="Feltoltes" name="submit">
    </form>