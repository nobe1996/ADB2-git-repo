
<html>
<head>
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
session_start();
/*
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

echo '<h2>Az Csoportok tábla adatai: </h2>';

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement
$stid = oci_parse($conn, 'SELECT * FROM Csoportok');
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_execute($stid);
print "<table border='1'>\n";
//// -- eloszor csak az oszlopneveket kerem le
$nfields = oci_num_fields($stid);
echo '<tr>';
for ($i = 1; $i<=$nfields; $i++){
    $field = oci_field_name($stid, $i);
    echo '<td>' . $field . '</td>';
}
echo '</tr>';

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid1);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch the results of the query
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);


$stid = oci_parse($conn, 'INSERT INTO CSOPORTOK (cs_nev) VALUES(\'Nevtelenek1\')');

$r = oci_execute($stid);  // executes and commits

if ($r) {
    print "One row inserted";
}

oci_free_statement($stid);


echo '<br>';
$stid = oci_parse($conn, 'SELECT * FROM KATEGORIAK');
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_execute($stid);
print "<table border='1'>\n";
//// -- eloszor csak az oszlopneveket kerem le
$nfields = oci_num_fields($stid);
echo '<tr>';
for ($i = 1; $i<=$nfields; $i++){
    $field = oci_field_name($stid, $i);
    echo '<td>' . $field . '</td>';
}
echo '</tr>';

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid1);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch the results of the query
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);



oci_close($conn);
*/
/*

$usr=$_POST["username"];
$pass=$_POST["password"];

$loggedin = False;

$tns = "
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 127.0.0.1)(PORT = 1521))
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

$stid = oci_parse($conn, "SELECT count(*) FROM FELHASZNALOK WHERE FELHASZNALONEV LIKE '" . $usr ."' AND JELSZO LIKE '" . $pass ."'");

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
		$loggedin = True;
	}
}
oci_free_statement($stid);

oci_close($conn);

*/

include "login.php";
?>
</body>
</html>
