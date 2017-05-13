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
<title>Test</title>
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
	
	echo "You have logged in as: ". $_SESSION['login_name'];
	if(isset($_SESSION['message'])){
		echo "<script type='text/javascript'>alert('Failed to upload!')</script>";
		unset($_SESSION['message']);
	}
	?>

<div id ="container">
	
<h3>IMGBoard</h3>

    <div id = "navdiv">
                    <ul class = "mainlinks">
					<li><a href="indexpage.php?logout">Logout</a></li> 
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
					<form action="filterLocation.php" method="post">
						<select>
							<option value="" disabled="disabled" selected="selected">Location</option>
							<option value="Szeged">Szeged</option>
							<option value="Budapest">Budapest</option>
						</select>
						<input type="submit">
					</form>
					<br />
					Filter by rating:
					<form action="filterRating.php" method="post">
						<select>
							<option value="5" disabled="disabled" selected="selected">5 stars</option>
							<option value="4">4 stars</option>
							<option value="3">3 stars</option>
							<option value="2">2 stars</option>
							<option value="1">1 stars</option>
						</select>
						<input type="submit">
					</form>
					<br />
					<hr />
			</div>
			
			<?php 
				if(isset($_POST['selectuserpic'])){
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV  FROM KEPEK WHERE FELHASZNALONEV LIKE '". $_POST['selectuserpic'] . "' ORDER BY URL");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						$imag = explode("/", $row["URL"]);
						echo '<li>'. $row["FELHASZNALONEV"] .'<a href="indexpage.php?bigname='.$imag[1] .'"><img onclick="switchMenu("bigpic"); displayDiv("bigpicture");" src = "'. $row["URL"].'"/></a></li>';
					} 
					
				}else{
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV  FROM KEPEK ORDER BY URL");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						$imag = explode("/", $row["URL"]);
						echo '<li>'. $row["FELHASZNALONEV"] .'<a href="indexpage.php?bigname='.$imag[1] .'"><img onclick="switchMenu("bigpic"); displayDiv("bigpicture");" src = "'. $row["URL"].'"/></a></li>';
					} 
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
			Votes: <br />
			Place: <br />
			</div>

			<div class="newPlace">
				Add a new place:
				<form action="newPlace.php" method="post">
						<input type="text" value="country"><br />
						<input type="text" value="state"><br />
						<input type="text" value="city"><br/>
						<input type="submit" value="Add Place">
					</form>
			</div>

		</div>
		
		
		<div id= "upload" class="upload">
			<?php
				include_once("upload.php");
			?>
		</div>

		
		<div id="allTimeTop" class="allTimeTop">
			<form action="topPlace.php" method="post">
 			<input type="submit" value="Top hely">
			</form>

			<form action="topCategory.php" method="post">
				<input type="submit" value="Top kategória">
			</form>

			<form action="topUser.php" method="post">
 			<input type="submit" value="Top felhasználó">
			</form>

			<form action="topPic.php" method="post">
				<input type="submit" value="Top kép">
 		</form>
		
		<?php 
		
		$stid1 = oci_parse($conn, "SELECT * FROM (SELECT FELHASZNALONEV, COUNT(FELHASZNALONEV) AS DARAB FROM KEPEK GROUP BY FELHASZNALONEV ORDER BY DARAB DESC) WHERE rownum = 1");
					oci_execute($stid1);
			while ($row = oci_fetch_assoc($stid1)) { 
					echo "A legtöbb képpel rendelkező felhasználó: " . $row['FELHASZNALONEV']. ", és " . $row['DARAB']. " darab képpel rendelkezik.";
					echo "<br>";
			}
			oci_free_statement($stid1);	
		
		$stid = oci_parse($conn, "SELECT KAT_NEV, COUNT(KAT_NEV) AS DARAB FROM KEPEK GROUP BY KAT_NEV");
					oci_execute($stid);
					print "<br><table border='1'>";
					echo '<tr>';
						echo '<th>Kategória</th>';
						echo '<th>Darab</th>';
					echo '</tr>';
					
					while ($row = oci_fetch_assoc($stid)) { 
						echo "<tr>";
						echo '<td>'. $row["KAT_NEV"] . '</td><td>'.$row["DARAB"] .'</td>';
						echo "</tr>";
					} 
			oci_free_statement($stid);	
		?>
		</div>
		
		<div id="bigpicture" class="bigpicture">
		<?php 
		if(isset($_GET['bigname'])){
			echo "<img src='images/".$_GET['bigname']."'/>";
			?>
			<form method="post" action="">
				Komment szövege:<input type="text" name="comment" value="" maxlength="100">
				<input type="submit" name="sendcomment" value="Kommentel"/>
			</form>
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
			}
			echo "<div class='comments'>";
			$stid1 = oci_parse($conn, "SELECT FELHASZNALONEV, KOMMENT  FROM KOMMENT WHERE URL LIKE 'images/" .$_GET["bigname"]."' ORDER BY KOMMENT_ID");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo "<div class='comment'><p class='user'> ".$row["FELHASZNALONEV"]."</p><p class='commentText'>".$row["KOMMENT"]."</p></div>";
					} 
			echo "</div>";
			
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
		<div id="rate">
 			<form action="ratePic.php" method="post">
  				Points:
   				<input type="range" name="rating" min="1" max="5">
   				<input type="submit" value="ok">
 			</form>
 		</div>
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
	displayDiv('bigpicture'); 
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