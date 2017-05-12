<?php
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
