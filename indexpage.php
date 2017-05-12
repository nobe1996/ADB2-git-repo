<?php
session_start();
include_once('dbconnect.php');
if (isset($_GET['logout'])){
	unset($_SESSION['login']);
	unset($_SESSION['login-name']);
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
		var bodyElements = ["picturelist", "userinfo", "bigpicture", "comments"]; //add more if needed
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
if($_SESSION['login']){?>

<div id ="container">
	
<h3>IMGBoard</h3>

    <div id = "navdiv">
                    <ul class = "mainlinks">
                    <li><a id="bigpic" onClick="switchMenu(this.id); displayDiv('bigpicture');">big picture</a></li>
                    <li><a id="infoButton" onClick="switchMenu(this.id); displayDiv('userinfo');">user</a></li>
                    <li><a id="pictureListButton" onClick="switchMenu(this.id); displayDiv('picturelist');">list 2</a></li>
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

			<li>picture<a href="#"><img onclick="switchMenu('bigpic'); displayDiv('bigpicture');" src = "images/autumntree.jpg"/></a></li>

			
			<li>picture<a href="#"><img src = "images/autumntree.jpg"/></a></li>
			<li>picture<a href="#"><img src = "images/autumntree.jpg"/></a></li>
			<li>pictures<a href="#"><img src = "images/autumntree.jpg"/></a></li>
			<li>picture<a href="#"><img src = "images/autumntree.jpg"/></a></li>
			<li>picture<a href="#"><img src = "images/autumntree.jpg"/></a></li>
			<li>picture<a href="#"><img src = "images/autumntree.jpg"/></a></li>
		</ul>
		<ul id= "userinfo" class="userinfo">
			<p>Username: asd</p>
			<p> pictures: 420</p>
			<p> votes: 69</p>
		</ul>
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
	include("login1.php");
}
?>
</body>
</html>