<?php
class Database
{
    private $db;
    private $connString;
    public function Database($host, $sid, $username, $password) {
        $this->connString = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521)))(CONNECT_DATA=(SID=".$sid.")))";
        $this->db = oci_connect($username, $password, $this->connString);
        if (!$this->db) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            die;
        }
    }

    /**
     * Az SQL SELECT megvalósítása, amely a teljes SQL parancsot kéri (amely SELECT) és az SQL lekérdezés
     * eredményével tér vissza többdimenziós tömbben.
     *
     * @param $sqlQuery - Az SQL lekérdezés
     * @return array - A többdimenziós tömb
     */
    public function select($sqlQuery){
        if(strtolower(strtok($sqlQuery, " ")) != "select"){
            return null;
        }
        $query = @oci_parse($this->db, $sqlQuery);
        $r = oci_execute($query);
        if(!$r){
            $e = oci_error($query);
            print $e["message"];
            return null;
        }
        $retArray = oci_fetch_array($query, OCI_ASSOC);
        @oci_free_statement($query);
        return $retArray;
    }

    /**
     * A táblába beír egy új rekordot a paraméterben kapott tömb alapján
     * @param $table - A tábla, amibe fel szeretnénk venni a rekordot
     * @param $array - A tömb, amiből a táblát feltöltjük
     * @return Mixed - Az eredmény (true|false)
     */
    public function insert($table, $array){
        $keyInArray = null;
        $valueInArray = null;
        foreach($array as $key => $value)
        {
            $keyInArray[] = '"'.$key.'"';
            $valueInArray[] = "'".$value."'";
        }

        $columnsNames = implode(', ', $keyInArray);
        $columnsValues = implode(", ", $valueInArray);
        $sqlText = 'INSERT INTO "'.$table.'" ('.$columnsNames.') VALUES ('.$columnsValues.')';
        $insQuery = oci_parse($this->db, $sqlText);
        $r = oci_execute($insQuery);
        if (!$r) {
            $e = oci_error($insQuery);
            $param1 = $e;
            $param2 = htmlentities($e['message']);
            $param2.= "<pre>\n";
            $param2.= htmlentities($e['sqltext']);
            $param2.= sprintf("\n%".($e['offset']+1)."s", "^");
            $param2.= "\n</pre>";
            return $param1;
        }
        return $r;
    }

    /**
     * Bármilyen SQL utasítást, amelynek nincs visszatérési értéke lefuttat.
     * Például: UPDATE, DELETE
     *
     * @param $sqlQuery - Maga az SQL utasítás
     * @return Mixed - Sikerült-e lefuttatni (true|false)
     */
    public function execute($sqlQuery){
        $insQuery = oci_parse($this->db, $sqlQuery);
        $r = oci_execute($insQuery);
        if (!$r) {
            $e = oci_error($insQuery);
            $param1 = $e;
            $param2 = htmlentities($e['message']);
            $param2.= "<pre>\n";
            $param2.= htmlentities($e['sqltext']);
            $param2.= sprintf("\n%".($e['offset']+1)."s", "^");
            $param2.= "\n</pre>";
            return $param1;
        }else{
            return true;
        }
    }
    public function __destruct() {
        oci_close($this->db);
    }
}