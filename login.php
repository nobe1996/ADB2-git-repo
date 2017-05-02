<?php
$usr=$_POST["username"];
$pass=$_POST["password"];

$usrsucc = False;
$passsucc = False;

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

$stid = oci_parse($conn, 'SELECT felhasznalonev FROM Felhasznalok WHERE felhasznalonev == "'$usr'"');

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
	if(count($row) == 1 ){
		$usrsucc = True;
	}
}
}
oci_free_statement($stid);

if($usrsucc){
	Echo "felhasznalonev helyes";
}

?>