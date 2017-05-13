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
		var bodyElements = ["picturelist", "userinfo", "upload","bigpicture", "comments"]; //add more if needed
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
			
		<form class="" action="" method="post">
			Felhasználó képeinek listázása:
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
			<?php 
				if(isset($_POST['selectuserpic'])){
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV  FROM KEPEK WHERE FELHASZNALONEV LIKE '". $_POST['selectuserpic'] . "'");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo '<li>'. $row["FELHASZNALONEV"] .'<a href="#"><img onclick="switchMenu("bigpic"); displayDiv("bigpicture");" src = "'. $row["URL"].'"/></a></li>';
					} 
				}else{
					$stid1 = oci_parse($conn, "SELECT URL, FELHASZNALONEV  FROM KEPEK");
					oci_execute($stid1);
					
					while ($row = oci_fetch_assoc($stid1)) { 
						echo '<li>'. $row["FELHASZNALONEV"] .'<a href="#"><img onclick="switchMenu("bigpic"); displayDiv("bigpicture");" src = "'. $row["URL"].'"/></a></li>';
					} 
				}
			?>
		</ul>

		<div id= "userinfo" class="userinfo">

			<div>
			Username: <br />
			Uploaded pictures: <br />
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

		</div>
		
		<div id="bigpicture" class="bigpicture">
			<img src="images/desertBig.jpg"/>

			<div class="comments">
				<div class="comment">
					<p class="user">
						username
					</p>
					<p class="commentText">
						this is a comment
					</p>
				</div>

				<div class="comment">
					<p class="user">
						username
					</p>
					<p class="commentText">
						aaaaaaaaaaaaaaaaaaaaaaaaaaaa
					</p>
				</div>
			</div>

		</div>
		<div id="comments" class="comments">
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
		</div>
	</div>
<script> 
	displayDiv(''); 
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