<?php
session_start();
include_once('dbconnect.php');
if (isset($_GET['logout'])){
	unset($_SESSION['login']);
	unset($_SESSION['login-name']);
	unset($_SESSION['message']);
}
if (!isset($_SESSION['login'])){
	$_SESSION['login'] = false;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<link href="main.css" rel="stylesheet" type="text/css" />
<title>IMGBoard</title>
<script type="text/javascript">
	function switchMenu(elementId){
		var allElements = document.getElementsByTagName("*");
			var allIds = [];
			for (var i = 0, n = allElements.length; i < n; ++i) {
				var el = allElements[i];
			if (el.id) {
				allIds.push(el.id);
			}
		}
		for(i = 0; i < allIds.length; i++){
			if(document.getElementById(allIds[i]).className.includes("selected")){
				document.getElementById(allIds[i]).className -= " selected";
			}
		}
		document.getElementById(elementId).className += " selected";
	}
	function displayDiv(whatToShow){
		var bodyElements = ["picturelist", "userinfo", "upload","bigpicture", "allTimeTop" ]; //add more if needed
		for(i = 0; i < bodyElements.length; i++){
			document.getElementById(bodyElements[i]).style.display = 'none';
		}
		document.getElementById(whatToShow).style.display = 'block';
	}
	function picFormToggle(){
		var d = document.getElementById("picForm");
		var b = document.getElementById("formButton");
		if(d.style.display == 'none'){
			d.style.display = 'block';
			b.style.opacity = '0.5';
		}else{
			d.style.display = 'none';
			b.style.opacity = '1';
		}
	}
</script>

</head>
<body>
<?php
if($_SESSION['login']){
	$user = $_SESSION['login_name'];
	echo "You have logged in as: ". $_SESSION['login_name'];
	if(isset($_SESSION['message'])){
		echo "<script type='text/javascript'>alert('Failed to upload!')</script>";
		unset($_SESSION['message']);
	}
	//echo "test";
	?>

<div id ="container">
	
<h3>IMGBoard</h3>

    <div id = "navdiv">
                    <ul class = "mainlinks">
					<li><a href="index.php?logout">Logout</a></li> 
                    <li><a id="bigpicButton" onClick="switchMenu(this.id); displayDiv('bigpicture');">big picture</a></li>
                    <li><a id="infoButton" onClick="switchMenu(this.id); displayDiv('userinfo');">user</a></li>
                    <li><a id="pictureListButton" onClick="switchMenu(this.id); displayDiv('picturelist');">Pictures</a></li>
					<li><a id="uploadButton" onclick="switchMenu(this.id); displayDiv('upload');">Upload</a></li>
					<li><a id="topButton" onclick="switchMenu(this.id); displayDiv('allTimeTop');">Top Pictures</a></li>
                </ul>
    </div>
</div>

	<div id="content">
		<ul id= "picturelist" class="picturelist">

			<p>
				<button id="formButton" onclick="picFormToggle()">Filter</button>
			</p>
			<div class="picForm" id="picForm">
					Filter by user:
					<form class="" action="" method="post">
					<select class="" name="selectuserpic">
						<option value="" selected disabled>Válassz...</option>
						<?php
							$stid1 = oci_parse($conn, "SELECT FELHASZNALONEV FROM FELHASZNALOK");
							oci_execute($stid1);
					
							while ($row = oci_fetch_assoc($stid1)) { 
								echo '<option value="'. $row["FELHASZNALONEV"] . '">'.$row["FELHASZNALONEV"] .'</option>'; 
							} 
						?>
					</select>
					<input type="submit" name="sendfelhasznalo" value="Lekérés" />
					</form>

					Filter by location: 
					
					<form class="" action="" method="post">
					<select class="" name="selectlocationpic">
						<option value="" selected disabled>Válassz...</option>
						<?php
							$stid1 = oci_parse($conn, "SELECT HELY_ID, ORSZAG, MEGYE, TELEPULES FROM HELYEK");
							oci_execute($stid1);
					
							while ($row = oci_fetch_assoc($stid1)) { 
								echo '<option value="'. $row["HELY_ID"] . '">'.$row["ORSZAG"] .  ', '. $row["MEGYE"] . ', '. $row["TELEPULES"] . '</option>'; 
							} 
						?>
					</select>
					<input type="submit" name="sendlocation" value="Lekérés" />
					</form>
				
					<br />
					<hr />
			</div>
			
			<?php 
				if(isset($_POST['selectuserpic'])){
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV, KAT_NEV  FROM KEPEK WHERE FELHASZNALONEV LIKE '". $_POST['selectuserpic'] . "' ORDER BY URL");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						$imag = explode("/", $row["URL"]);
						echo "<li>". $row["FELHASZNALONEV"] ." ". $row["KAT_NEV"] ."<a href='index.php?bigname=".$imag[1] ."'><img onclick='switchMenu('bigpic'); displayDiv('bigpicture');' src = '". $row["URL"]."'/></a></li>";
					} 
					echo "<div id='nav'>picturelist</div>";
					
				}else if(isset($_POST['selectlocationpic'])){
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV, KAT_NEV, HELY_ID  FROM KEPEK WHERE HELY_ID LIKE '". $_POST['selectlocationpic'] . "' ORDER BY URL");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						$imag = explode("/", $row["URL"]);
						echo "<li>". $row["FELHASZNALONEV"] ." ". $row["KAT_NEV"] ."<a href='index.php?bigname=".$imag[1] ."'><img onclick='switchMenu('bigpic'); displayDiv('bigpicture');' src = '". $row["URL"]."'/></a></li>";
					}
					echo "<div id='nav'>picturelist</div>";
				}
				else{
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV, KAT_NEV  FROM KEPEK ORDER BY URL");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						$imag = explode("/", $row["URL"]);
						echo "<li>". $row["FELHASZNALONEV"] ." ". $row["KAT_NEV"] ."<a href='index.php?bigname=".$imag[1] ."'><img onclick='switchMenu('bigpic'); displayDiv('bigpicture');' src = '". $row["URL"]."'/></a></li>";
					} 
					echo "<div id='nav'>picturelist</div>";
				}
				
				
				
				//echo "<script>switchMenu(pictureListButton); displayDiv('picturelist'); </script>";
			?>
		</ul>

		<div id= "userinfo" class="userinfo">

			<div>
			Username: <?php echo $_SESSION['login_name']; ?> <br />
			Uploaded pictures: 
			<?php 
				$stmt= oci_parse($conn, "SELECT COUNT(URL) AS NUMBER_OF_PICTURES FROM KEPEK WHERE FELHASZNALONEV LIKE '".$_SESSION['login_name']."'");
			oci_define_by_name($stmt, 'NUMBER_OF_PICTURES', $number_of_pictures);
			oci_execute($stmt);
			oci_fetch($stmt);
			echo $number_of_pictures;
			?>
			<br />
			</div>

			<div class="newPlace">
				Adj hozzá helyet:
				<form action="" method="post">
                        <input type="number" name="id0" value="Iranyitoszam"><br />
						<input type="text" name="orszag" value="Orszag"><br />
						<input type="text" name="megye" value="Megye"><br />
						<input type="text" name="varos" value="Varos"><br/>
						<input type="submit" name="hely" value="Ok">
					</form>
			</div>
			<div class="newCategory">
				Adj hozzá kategóriát:
				<form action="" method="post">
						<input type="text" name="id1" value="category"><br />
						<input type="submit" name="kategoria" value="Ok">
					</form>
			</div>
			<div class="newGroup">
				Adj hozzá csoportot:
				<form action="" method="post">
						<input type="text" name="id2" value="group"><br />
						<input type="submit" name="csoport" value="Ok">
					</form>
			</div>
			
            <?php
            if(isset($_POST['hely'])){
                $str = "'" . $_POST['id0']. "','" . $_POST['orszag'] . "','" . $_POST['megye'] . "','" . $_POST['varos'] . "'";
                echo $str;
		$stid = oci_parse($conn, "INSERT INTO HELYEK (HELY_ID, ORSZAG, MEGYE, TELEPULES) VALUES (" . $str . ")");
                oci_execute($stid);
		
            }else if(isset($_POST['kategoria'])){
                $str = "'" . $_POST['id1']. "'";
                $stid = oci_parse($conn, 'INSERT INTO KATEGORIAK (KAT_NEV) VALUES (' . $str . ')');
                oci_execute($stid);

            }else if(isset($_POST['csoport'])){
                $str = "'" . $_POST['id2']. "'";
                $stid = oci_parse($conn, 'INSERT INTO CSOPORTOK (CS_NEV) VALUES (' . $str . ')');
                oci_execute($stid);
            }
            ?>

			<?php 
			//echo "<div id='nav'>userinfo</div>";
			?>

		</div>
		
		
		<div id= "upload" class="upload">
			<?php
				include_once("upload.php");
				//echo "<div id='nav'>picturelist</div>";
			?>
		</div>

		
		<div id="allTimeTop" class="allTimeTop">
			<script>
				function sw(show){
					document.getElementById("topkat").style.display = 'none';
					document.getElementById("topfelh").style.display = 'none';
					document.getElementById("tophely").style.display = 'none';
					
					document.getElementById(show).style.display = 'block';
				}

			</script>
		
			<button onclick='sw("tophely")'>Top hely</button>

			<button onclick='sw("topkat")'>Top kategória</button>

 			<button onclick='sw("topfelh")'>Top felhasználó</button>
		
		<?php 
		
			$stid1 = oci_parse($conn, "SELECT * FROM (SELECT FELHASZNALONEV, COUNT(FELHASZNALONEV) AS DARAB FROM KEPEK GROUP BY FELHASZNALONEV ORDER BY DARAB DESC) WHERE rownum = 1");
			oci_execute($stid1);
			while ($row = oci_fetch_assoc($stid1)) { 
					echo "A legtöbb képpel rendelkező felhasználó: " . $row['FELHASZNALONEV']. ", és " . $row['DARAB']. " darab képpel rendelkezik.";
					echo "<br>";
			}
		
		$stid = oci_parse($conn, "SELECT KAT_NEV, COUNT(KAT_NEV) AS DARAB FROM KEPEK GROUP BY KAT_NEV ORDER BY COUNT(KAT_NEV) DESC");
					oci_execute($stid);
					echo "<div id='topkat'>";
					echo "<br><table border='1'>";
					echo '<tr>';
						echo '<th>Kategória</th>';
						echo '<th>Darab</th>';
					echo '</tr>';
					
					while ($row = oci_fetch_assoc($stid)) { 
						echo "<tr>";
						echo '<td>'. $row["KAT_NEV"] . '</td><td>'.$row["DARAB"] .'</td>';
						echo "</tr>";
					} 
					
					echo "</table>";
					echo "</div>";

				$stid = oci_parse($conn, "SELECT FELHASZNALONEV, COUNT(FELHASZNALONEV) AS DARAB FROM KEPEK GROUP BY FELHASZNALONEV ORDER BY COUNT(FELHASZNALONEV) DESC");
					oci_execute($stid);
					echo "<div id='topfelh'>";
					echo "<br><table border='1'>";
					echo '<tr>';
						echo '<th>Felhasználó</th>';
						echo '<th>Darab</th>';
					echo '</tr>';
					
					while ($row = oci_fetch_assoc($stid)) { 
						echo "<tr>";
						echo '<td>'. $row["FELHASZNALONEV"] . '</td><td>'.$row["DARAB"] .'</td>';
						echo "</tr>";
					} 

					
					echo "</table>";
					echo "</div>";

				$stid = oci_parse($conn, "SELECT HELY_ID, COUNT(HELY_ID) AS DARAB FROM KEPEK GROUP BY HELY_ID ORDER BY COUNT(HELY_ID) DESC");
					oci_execute($stid);
					echo "<div id='tophely'>";
					echo "<br><table border='1'>";
					echo '<tr>';
						echo '<th>Hely</th>';
						echo '<th>Darab</th>';
					echo '</tr>';
					
					while ($row = oci_fetch_assoc($stid)) { 
						echo "<tr>";
						echo '<td>'. $row["HELY_ID"] . '</td><td>'.$row["DARAB"] .'</td>';
						echo "</tr>";
					} 

					
					echo "</table>";
					echo "</div>";
					
					//echo "<div id='nav'>allTimeTop</div>";
		?>
			
			<script>
				sw('');
			</script>
		</div>
		
		<div id="bigpicture" class="bigpicture">
		<?php 
		if(isset($_GET['bigname'])){
			echo "<img src='images/".$_GET['bigname']."'/>";
			
			$stid2 = oci_parse($conn, "SELECT FELHASZNALONEV FROM KEPEK WHERE URL LIKE 'images/". $_GET['bigname'] ."'");
			oci_execute($stid2);
			while ($row = oci_fetch_assoc($stid2)) { 
					echo "Készítette: ". $row["FELHASZNALONEV"];
			}
			
			
			$stid2 = oci_parse($conn, "SELECT ERTEKELES, FELHASZNALONEV FROM ERTEKELESEK WHERE URL LIKE 'images/". $_GET['bigname'] ."' AND FELHASZNALONEV LIKE '". $user."'");
			oci_execute($stid2);
			while ($row = oci_fetch_assoc($stid2)) { 
					if($row["ERTEKELES"] == ""){
						echo "<br>";
						echo "Még nem értékelted a képet";
						echo "<br>";
					}else {
						echo "<br>";
						echo "Értékelésed: ". $row["ERTEKELES"];
						echo "<br>";
					}
			}
			
			?>
			<form method="post" action="">
				Komment szövege:<input type="text" name="comment" value="" maxlength="100">
				<input type="submit" name="sendcomment" value="Kommentel"/>
			</form>
			
			<div id="rate">
 			<form action="" method="post">
  				Points:
   				<input type="range" name="rating" min="1" max="5">
   				<input type="submit" name="sendrating" value="OK">
 			</form>
			</div>
		<?php
			if(isset($_POST['sendcomment'])){
					$stmt= oci_parse($conn, "SELECT COUNT(KOMMENT_ID) AS NUMBER_OF_KOMMENT FROM KOMMENT");
					oci_define_by_name($stmt, 'NUMBER_OF_KOMMENT', $number_of_komment);
					oci_execute($stmt);
					oci_fetch($stmt);
					
					$komment_id = $number_of_komment +1;
					$values = "'".$komment_id."','".htmlspecialchars($_POST["comment"])."','". $_SESSION['login_name']."', 'images/". $_GET['bigname'] ."'";
					$stid = oci_parse($conn, 'INSERT INTO KOMMENT (KOMMENT_ID, KOMMENT, FELHASZNALONEV, URL) VALUES ('.$values.')');
					oci_execute($stid);	
					echo "<div id='nav'>bigpicture</div>";
					unset($_POST['sendcomment']);
			}
			//echo "<div id='nav'>bigpicture</div>";
			echo "<div class='comments'>";
			$stid1 = oci_parse($conn, "SELECT FELHASZNALONEV, KOMMENT  FROM KOMMENT WHERE URL LIKE 'images/" .$_GET["bigname"]."' ORDER BY KOMMENT_ID");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo "<div class='comment'><p class='user'> ".$row["FELHASZNALONEV"]."</p><p class='commentText'>".$row["KOMMENT"]."</p></div>";
					} 
			echo "</div>";
			
			
				if(isset($_POST['sendrating'])){
			
					$stmt= oci_parse($conn, "SELECT COUNT(FELHASZNALONEV) AS NUMBER_OF_RATING FROM ERTEKELESEK WHERE FELHASZNALONEV LIKE '" . $user ."' AND URL LIKE 'images/". $_GET['bigname'] ."'");
					oci_define_by_name($stmt, 'NUMBER_OF_RATING', $number_of_rating);
					oci_execute($stmt);
					oci_fetch($stmt);
					
					if($number_of_rating  == 0){
						$values = "'". $user."','images/".$_GET['bigname']."','".htmlspecialchars($_POST["rating"])."'";
						$stid = oci_parse($conn, 'INSERT INTO ERTEKELESEK (FELHASZNALONEV, URL, ERTEKELES) VALUES ('.$values.')');
						oci_execute($stid);
						
					}
					else{
						$stid1 = oci_parse($conn, "UPDATE ERTEKELESEK SET ERTEKELES = '". $_POST['rating'] ."' WHERE FELHASZNALONEV LIKE '". $user . "' AND URL LIKE 'images/". $_GET['bigname'] ."'");
						oci_execute($stid1);
						
					}
					//echo "<div id='nav'>bigpicture</div>";
					unset($_POST['sendrating']);
		}
			
			
		}else{
			/*$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV  FROM KEPEK ORDER BY URL");
					oci_execute($stid1);
					echo "<ul>";
					while ($row = oci_fetch_assoc($stid1)) { 
						
						echo '<li><img src = "'. $row["URL"].'"/></li></br>';
					} 
					echo "</ul>";*/
					echo "<img src='images/splash.jpg'>";	
		}
		?>
		</div>
	
		<!--<div id="comments" class="comments">
			<div class="comment">
				<p class="user">
					username
				</p>
				<p class="commentText">
					this is a comment
				</p>
			</div>
			<div class="comment">
				<a href="#"><p class="user">
					username2
				</p></a>
				<p class="commentText">
					this<br/>is<br/>another<br/>comment
				</p>
			</div>
		</div>-->
	</div>
<script> 
	displayDiv('bigPicture');
	/*try{
 		var temp = document.getElementById('nav');
 		var nv = temp.innerHTML;
 		temp.innerHTML = '';
 		displayDiv(nv);
 	}catch(err){
 		displayDiv('bigPicture');	
 	}*/
</script>
<?php 
} 
else{
	include_once("login1.php");
}
oci_close($conn);
?>
</body>
</html>
