<?php
$usr=$_POST["username"];
$pass=$_POST["password"];

$loggedin = False;

$tns = "
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = irinyi.cloud)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SID = kabinet)
    )
  )";
  
$conn = oci_connect('h669113', 'h669113', $tns,'UTF8') or die();

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT COUNT(*) AS RETURN FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $usr ."' AND JELSZO LIKE '" . $pass ."'");

if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);

if (!$r) {
    $e = oci_error($stid1);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	if($row['RETURN'] == 1 ){
		$loggedin = True;
	}
}

if($loggedin){
	$_SESSION['login'] = true;
	$_SESSION['login_name'] = $usr;
}


oci_free_statement($stid);

oci_close($conn);


if($_SESSION['login']){
	echo "Logged in";
}
?>