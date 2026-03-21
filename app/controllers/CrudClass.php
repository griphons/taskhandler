<?php
namespace App\Controllers;
use PDO;
use PDOException;
class CRUD extends PDO
{
    protected $qs;
    protected $binds;
    protected array $CRUD;

    public function __construct() {
        if($_ENV['DB_DRIVER'] == 'sqlite') {
            // Construct the parent PDO class with SQLite DSN
            parent::__construct("sqlite:" . __DIR__ . $_ENV["DB_FILE"]);
        } else {
            $dns = $_ENV["DB_DRIVER"].':host=' . $_ENV["DB_HOST"] . ';port=' . $_ENV["DB_PORT"] . ';dbname=' . $_ENV["DB_NAME"];
            parent::__construct($dns, $_ENV["DB_USER"], $_ENV["DB_PASS"]);
        }

        // Set default attributes for modern error handling and fetching
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->CRUD = [];
    }

    /**
     * DB Error Exception
     * @param $message
     * @param $trace
     * @return string
     */
    protected function myException($message,$trace): string
    {
        $msg = '<div class="DbErrorBlock"><h3>Database Error</h3><pre>';
        $msg .= '<p>'.$message.'</p>';
        $msg .= '<p><u>Query:</u><br>'.$this->qs.'</p>';
        $msg .= '<p><u>Parameters:</u><br>';
        if (count($this->binds) > 0) {
            $msg .= var_export($this->binds, true).'<p>';
            $tsQ = $this->qs;
            foreach($this->binds as $bl => $value) {
                if (is_numeric($value)) $tsQ = str_replace($bl,$value,$tsQ);
                else $tsQ = str_replace($bl,"'".$value."'",$tsQ);
            }
            $msg .= "<p><u>Full query:</u><br>[".$tsQ."]";
        } else {
            $msg .= "N/A";
        }
        $msg .= '</p>';
        $msg .= '<p><u>Traced Errorpath:</u><br>'.$trace.'</p>';
        $msg .= '</pre></div>';
        return $msg;
    }

    /**
     * A helper to run a query with params and return the statement
     */
    public function run(string $sql, array $params = []): false|\PDOStatement
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Prepare Empty Record for Create
     * @param $table
     * @return array
     */
    public function nulldat($table): array
    {
        $data = [];
        $select = $this->query("SELECT * FROM `".$table."`");
        $ndx = 0;
        while ($meta = $select->getColumnMeta($ndx)) {
            $label = $meta["name"];
            switch($meta["native_type"]){
                case "TINY" :
                case "SHORT" :
                case "LONG" :
                case "INT24" :
                case "LONGLONG" :
                case "FLOAT" :
                case "NEWDECIMAL" :
                case "DOUBLE" :
                case "BIT" :
                case "REAL" :
                case "SERIAL" :
                    $default = 0;
                    break;
                default :
                    $default = "";
            }
            if ($label == "id") {$default = NULL;}
            $data[$label] = $default;
            $ndx++;
        }
        return($data);
    }

    /**
     * Read One Record
     * @param $table
     * @param $condition
     * @param $cells
     * @return false|mixed
     */
    public function getRow($table, $condition = '1', $cells = '*') {
        $query = "SELECT ".$cells." FROM `".$table."` WHERE ".$condition;
        try {
            $req = $this->query($query);
            return $req->fetch(PDO::FETCH_ASSOC);
        }
        catch( PDOException $Exception ) {
            echo $this->myException($Exception->getMessage(),$Exception->getTraceAsString());
            return false;
        }
    }

    /**
     * Read One Record by ID
     * @param $table
     * @param $id
     * @return false|mixed
     */
    public function getRowById($table, $id) {
        $query = "SELECT * FROM `".$table."` WHERE `id` = :id ";
        try {
            $req = $this->prepare($query);
            $req->bindParam(":id", $id);
            return $req->fetch(PDO::FETCH_ASSOC);
        }
        catch( PDOException $Exception ) {
            echo $this->myException($Exception->getMessage(),$Exception->getTraceAsString());
            return false;
        }
    }

    /**
     * Make it array the cell list
     * @param $cells
     * @return array
     */
    protected function cellSelect($cells): array
    {
        return is_array($cells) ? $cells : [ $cells];
    }

    /* CRUD OPERATIONS */

    /**
     * Create - return the empty associative array of a new row of the specified table
     * @param $data - associative array of default values of the row
     * @return $this
     */
    public function create($data = []) {
        $this->CRUD = [];
        $this->CRUD['operation'] = "empty";
        $this->CRUD['data'] = $data;
        return $this;
    }
    /* CRUD OPERATIONS */
    /* Read - return the query result by fetchAll(PDO::FETCH_ASSOCC)
     * params
     * $cells - array of cell names - array of strings
     */
    public function reads($cells = "*") {
        $this->CRUD = [];
        $this->CRUD['operation'] = "reads";
        $this->CRUD['cells'] = $this->cellSelect($cells);
        return $this;
    }
    /* Read - return the query result by fetch(PDO::FETCH_ASSOCC)
     * params
     * $cells - array of cell names - array of strings
     */
    public function read($cells = "*") {
        $this->CRUD = [];
        $this->CRUD['operation'] = "read";
        $this->CRUD['cells'] = $this->cellSelect($cells);
        return $this;
    }
    /* Select - return the query object
     * params
     * $cells - array of cell names - array of strings
     * can use: "DISTINCT cellname"
     */
    public function select($cells = "*") {
        $this->CRUD = [];
        $this->CRUD['operation'] = "select";
        $this->CRUD['cells'] = $this->cellSelect($cells);
        return $this;
    }
    /* Distinct  - return the query result by fetchAll(PDO::FETCH_ASSOCC)
     * params
     * $cell - cell name of distincted (NOT YET COMPLETED)
     */
    public function distinct($cell) {
        $this->CRUD = [];
        $this->CRUD['operation'] = "distinct";
        $this->CRUD['cells'] = [$cell];
        return $this;
    }
    /* params
    $data - associative array of updating data (keys for cell names, values for values)
    */
    public function update($data) {
        $this->CRUD = [];
        $this->CRUD['operation'] = "update";
        $this->CRUD['data'] = $data;
        return $this;
    }
    /* params
    $data - associative array of updating data (keys for cell names, values for values)
    */
    public function insert($data) {
        $this->CRUD = [];
        $this->CRUD['operation'] = "create";
        $this->CRUD['data'] = $data;
        return $this;
    }
    /* params */
    public function delete() {
        $this->CRUD = [];
        $this->CRUD['operation'] = "delete";
        return $this;
    }
    /* params
    $table - table name - string
    */
    public function table($table) {
        if (isset($this->CRUD['operation'])) {
            $this->CRUD['table'] = $table;
        }
        return $this;
    }
    /* params
    $table - table name - string
    $cells - array of cell names - array of strings (w/o alias)
    $joincells - array of joiner cells w/o alias (first - main table cell, 2nd - joined table cell)
    */
    public function join($table, $cells, $joincells) {
        if (isset($this->CRUD['operation']) && $this->CRUD['operation'] != "create" && $this->CRUD['operation'] != "delete" && $this->CRUD['operation'] != "empty") {
            if (!isset($this->CRUD['join'])) {$this->CRUD['join'] = array();}
            if (!is_array($cells)) {$acells = array($cells);} else {$acells = $cells;}
            if (!is_array($joincells)) {$ajoincells = array("id",$joincells);} else {$ajoincells = $joincells;}
            $join = array(
                "table" => $table,
                "cells" => $acells,
                "on" => $ajoincells
            );
            $this->CRUD['join'][] = $join;
        }
        return $this;
    }
    /* params
    $conditions - conditionstring cells w alias
    sample:
    ->where("id","","=","15")
    ->where("age","another_table_name",">",25,"AND")
    ->where("name","","=","John Doe","AND")

    code syntax view:
    $conj . $table . $cell . $operand . $value

    */
    public function where($cell, $table, $operand = "=", $value = "", $conj = "") {
        if (isset($this->CRUD['operation']) && $this->CRUD['operation'] != "create" && $this->CRUD['operation'] != "empty") {
            if ($table == "") {$table = $this->CRUD["table"];}
            if (!isset($this->CRUD["condition"])) {$this->CRUD["condition"] = array();}
            if ($cell === false) {
                unset($this->CRUD["condition"]);
            } else {
                $this->CRUD["condition"][] = array(
                    "cell" => $cell,
                    "table" => $table,
                    "operand" => $operand,
                    "value" => $value,
                    "conj" => $conj
                );
            }
        }
        return $this;
    }
    /*
     * Simple condition on the table ID
     * use it only for the first of the conditions!
     * equivalent to:
     * ->where("id","","=",$IDValue)
     * */
    public function whereID($IDvalue) {
        if (isset($this->CRUD['operation']) && $this->CRUD['operation'] != "create" && $this->CRUD['operation'] != "empty") {
            $this->CRUD["condition"][] = array(
                "cell" => "id",
                "table" => $this->CRUD["table"],
                "operand" => "=",
                "value" => $IDvalue,
                "conj" => ""
            );
        }
        return $this;
    }

    /* where cap start */
    public function whereCS($conj = "") {
        $this->CRUD["condition"][] = array(
            "cell" => "",
            "table" => "",
            "operand" => "",
            "value" => "",
            "conj" => $conj." ("
        );
        return $this;
    }
    /* where cap end */
    public function whereCE() {
        $this->CRUD["condition"][] = array(
            "cell" => "",
            "table" => "",
            "operand" => "",
            "value" => "",
            "conj" => ")"
        );
        return $this;
    }

    /* params
    $from - from record (default 0) - int
    $number - number of records - int/false

    if $number not add, the $from value will be the number of records!
    */
    public function limit($from, $number = false) {
        if (isset($this->CRUD['operation']) && $this->CRUD['operation'] != "create" && $this->CRUD['operation'] != "read" && $this->CRUD['operation'] != "empty") {
            if ($from === false) {
                unset($this->CRUD["from"]);unset($this->CRUD["number"]);
            } else if ($number === false) {
                $this->CRUD["from"] = 0; $this->CRUD["number"] = intval($from);
            } else {
                $this->CRUD["from"] = intval($from); $this->CRUD["number"] = intval($number);
            }
        }
        return $this;
    }

    /* params
    $orderby - array of ordering arrays {[cell, orientation],[cell, orientation], ... }
    */
    public function orderby($orderby) {
        if (isset($this->CRUD['operation']) && $this->CRUD['operation'] != "create" && $this->CRUD['operation'] != "update" && $this->CRUD['operation'] != "empty") {
            if (is_array($orderby)) {
                if (!is_array($orderby[0])) {
                    $this->CRUD["orderby"] = array($orderby);
                } else {
                    $this->CRUD["orderby"] = $orderby;
                }
            } else if ($orderby === false) {
                unset($this->CRUD["orderby"]);
            }
        }
        return $this;
    }

    /* Debug - return query string and paramstrings
     * */
    public function debug() {
        $this->CRUD["debug"] = true;
        return $this;
    }

    /**
     * Get the names of the columns of the table
     * @param $table
     * @return array
     */
    private function getColumnNames($table) {
        $columns = [];
        if($_ENV["DB_DRIVER"] == "sqlite") {
            $cdt = $this->query("PRAGMA table_info(`users`)");
            $cls = $cdt->fetchAll(PDO::FETCH_ASSOC);
            foreach($cls as $c) $columns[] = $c["name"];
        } else {
            $cdt = $this->query("SHOW COLUMNS FROM `".$table."`");
            $cls = $cdt->fetchAll(PDO::FETCH_ASSOC);
            foreach($cls as $c) $columns[] = $c["Field"];
        }
        return $columns;
    }

    /**
     * Prepare and Execute Query
     * @return array|bool|mixed|\PDOStatement|string
     */
    public function get() {
        if (isset($this->CRUD['operation'])) {
            $this->binds = array();

            switch($this->CRUD['operation']) {
                case 'empty':
                    break;
                case 'create':
                    $this->qs = "INSERT INTO `".$this->CRUD["table"]."` (`".implode("`, `", array_keys($this->CRUD["data"]))."`) VALUES (:".implode(", :", array_keys($this->CRUD["data"])).")";
                    foreach ($this->CRUD["data"] as $key => $value) {
                        $this->binds[":".$key] = $value;
                    }

                    break;
                case 'reads':
                case 'read':
                case 'select':
                case 'distinct':
                    $cells = array();
                    if (count($this->CRUD["cells"]) == 1 && strpos($this->CRUD["cells"][0],"DISTINCT ") === 0) {
                        $cell = str_replace("DISTINCT ", "", $this->CRUD["cells"][0]);
                        $cellist = "DISTINCT `" . $this->CRUD["table"] . "`.`" . $cell . "`";
                    } else if ($this->CRUD['operation'] == "distinct") {
                        $cellist = "DISTINCT `" . $this->CRUD["table"] . "`.`" . $this->CRUD["cells"][0] . "`";
                    } else {
                        $gcs = [];
                        foreach($this->CRUD["cells"] as $L => $C) {
                            if ($C != '*') {
                                if (is_numeric($L)) {
                                    $cells[] = "`".$this->CRUD["table"]."`.`".$C."`";
                                } else {
                                    $cells[] = "`".$this->CRUD["table"]."`.`".$L."` as `".$C."`";
                                }
                                $gcs[] = $C;
                            } else {
                                $cells[] = "`".$this->CRUD["table"]."`.*";
                                $gcs = $this->getColumnNames($this->CRUD["table"]);
                            }
                            $scells[] = "`".$C."`";
                        }
                        if (isset($this->CRUD['join'])) {
                            foreach($this->CRUD['join'] as $J) {
                                foreach($J["cells"] as $L => $C) {
                                    if ($C == '*') {
                                        //$cells[] = "`".$J["table"]."`.*";
                                        $JC = $this->getColumnNames($J["table"]);
                                        foreach($JC as $j) {
                                            if (array_search($j,$gcs) !== false) {
                                                $finame = $J["table"]."_".$j;
                                                $cells[] = "`".$J["table"]."`.`".$j."` as `".$finame."`";
                                                $gcs[] = $finame;
                                            } else {
                                                $cells[] = "`".$J["table"]."`.`".$j."`";
                                                $gcs[] = $j;
                                            }
                                        }
                                    } else {
                                        if (is_numeric($L)) {
                                            if (array_search($C,$gcs) !== false) {
                                                $finame = $J["table"]."_".$C;
                                                $cells[] = "`".$J["table"]."`.`".$C."` as `".$finame."`";
                                                $gcs[] = $finame;
                                            } else {
                                                $finame = $C;
                                                $cells[] = "`".$J["table"]."`.`".$C."` as `".$finame."`";
                                                $gcs[] = $finame;
                                            }
                                        } else {
                                            if (array_search($C,$gcs) !== false) {
                                                $finame = $J["table"]."_".$C;
                                                $cells[] = "`".$J["table"]."`.`".$L."` as `".$finame."`";
                                                $gcs[] = $finame;
                                            } else {
                                                $finame = $C;
                                                $cells[] = "`".$J["table"]."`.`".$L."` as `".$finame."`";
                                                $gcs[] = $finame;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $cellist = implode(',',$cells);
                    }
                    $this->qs = "SELECT ".$cellist." FROM `".$this->CRUD["table"]."` ";
                    break;
                case 'update':
                    $this->qs = "UPDATE `".$this->CRUD["table"]."` SET ";
                    $addval = array();
                    foreach($this->CRUD["data"] as $cell => $value) {
                        if (is_numeric($value)) {
                            $addval[] = "`".$cell."` = ".$value;
                        } else {
                            $addval[] = "`".$cell."` = '".$value."'";
                        }
                    }
                    $this->qs .= implode(", ",$addval)." ";
                    break;
                case 'delete':
                    $this->qs = "DELETE FROM `".$this->CRUD["table"]."` ";
                    break;
            }

            if (isset($this->CRUD['join'])) {
                $joins = array();
                foreach($this->CRUD['join'] as $J) {
                    $joins[] = "LEFT JOIN `".$J["table"]."` ON `".$this->CRUD["table"]."`.`".$J["on"][0]."` = `".$J["table"]."`.`".$J["on"][1]."` ";
                }
                $this->qs .= implode("", $joins);
            }

            if (isset($this->CRUD["condition"])) {
                $conditions = []; $num = 1;
                foreach ($this->CRUD["condition"] as$C) {
                    if ($C["conj"] !== "") {
                        $conditions[] = $C["conj"]." ";
                    }
                    if (strpos($C["conj"],"(") === false && strpos($C["conj"],")") === false) {
                        $_cell = $C["cell"];
                        if ($C["operand"] == "IN" || $C["operand"] == "NOT IN") {
                            $conditions[] = "`" . $C["table"] . "`.`" . $_cell . "` ".$C["operand"]." ('" . implode("','", $C["value"]) . "') ";
                        } else {
                            $varname = ":cond".$num;
                            if ($C["operand"] == "LIKE") {
                                $_value = "%".$C["value"]."%";
                                $_operand = "LIKE";
                            } else if ($C["operand"] == "LIKE_") {
                                $_value = $C["value"]."%";
                                $_operand = "LIKE";
                            } else if ($C["operand"] == "_LIKE") {
                                $_value = "%".$C["value"];
                                $_operand = "LIKE";
                            } else {
                                $_value = $C["value"];
                                $_operand = $C["operand"];
                            }
                            $conditions[] = "`".$C["table"]."`.`".$_cell."` ".$_operand." ".$varname." ";
                            $this->binds[$varname] = $_value;
                            $num++;
                        }
                    }
                }
                $conditionString = implode(" AND ", $conditions);
                $this->qs .= "WHERE ".$conditionString." ";
            }

            if (isset($this->CRUD["orderby"])) {
                $orders = array();
                foreach($this->CRUD["orderby"] as $O) {
                    if (strtolower($O[0]) == "rand()")
                        $orders[] = $O[0];
                    else
                        $orders[] = "`".$this->CRUD["table"]."`.`".$O[0]."` ".$O[1];
                }
                $this->qs .= "ORDER BY ".implode(", ",$orders)." ";
            }

            if (isset($this->CRUD["from"])) {
                $this->qs .= "LIMIT ".$this->CRUD["from"].", ".$this->CRUD["number"]." ";
            }
            if ($this->CRUD['operation'] == "read") {
                $this->qs .= "LIMIT 1 ";
            }

            if ($this->CRUD['operation'] == 'empty') {
                $data = [];
                $select = $this->query("SELECT * FROM `".$this->CRUD['table']."`");
                $ndx = 0;
                while ($meta = $select->getColumnMeta($ndx)) {
                    $label = $meta["name"];
                    if (isset($this->CRUD["data"][$label])) {
                        $default = $this->CRUD["data"][$label];
                    } else {
                        switch($meta["native_type"]){
                            case "TINY" :
                            case "SHORT" :
                            case "LONG" :
                            case "INT24" :
                            case "LONGLONG" :
                            case "FLOAT" :
                            case "NEWDECIMAL" :
                            case "DOUBLE" :
                            case "BIT" :
                            case "REAL" :
                            case "SERIAL" :
                                $default = 0;
                                break;
                            default :
                                $default = "";
                        }
                        if ($label == "id") {$default = NULL;}
                    }
                    $data[$label] = $default;
                    $ndx++;
                }
                return($data);
            } else if (isset($this->CRUD['debug'])) {
                $retString = "Query string:<br>[" . $this->qs . "]<br>";
                if (count($this->binds) > 0) {
                    $retString .= "Params:<br>" . var_export($this->binds, true);
                    $tsQ = $this->qs;
                    foreach ($this->binds as $bl => $value) {
                        if (is_numeric($value)) $tsQ = str_replace($bl, $value, $tsQ);
                        else $tsQ = str_replace($bl, "'" . $value . "'", $tsQ);
                    }
                    $retString .= "<br>Full Query: [" . $tsQ . "]";
                } else {
                    $retString .= "no Params";
                }
                return $retString;
            } else {
                $query = $this->prepare($this->qs);
                if (count($this->binds) > 0) {
                    foreach ($this->binds as $label => $val) {
                        if (is_numeric($val)) {
                            if (is_float($val)) {
                                $query->bindValue($label, $val);
                            } else {
                                $query->bindValue($label, $val, PDO::PARAM_INT);
                            }
                        } else {
                            $query->bindValue($label, $val, PDO::PARAM_STR);
                        }
                    }
                }
                try {
                    $req = $query->execute();
                    if ($this->CRUD['operation'] == "create") {
                        $respond = $this->lastInsertId();
                    } else if ($this->CRUD['operation'] == "read") {
                        $respond = $query->fetch(PDO::FETCH_ASSOC);
                    } else if ($this->CRUD['operation'] == "reads") {
                        $respond = $query->fetchAll(PDO::FETCH_ASSOC);
                    } else if ($this->CRUD['operation'] == "select" || $this->CRUD['operation'] == "distinct") {
                        $respond = $query;
                    } else {
                        $respond = $req;
                    }
                    return $respond;
                }
                catch( PDOException $Exception ) {
                    echo $this->myException($Exception->getMessage(),$Exception->getTraceAsString());
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Get the Query
     * @return mixed
     */
    public function getQS() {
        return $this->qs;
    }

    /**
     * get full query debug
     * @return string
     */
    public function getDebug() {
        $retString = "Query string:<br>[" . $this->qs . "]<br>";
        if (count($this->binds) > 0) {
            $retString .= "Params:<br>" . var_export($this->binds, true);
            $tsQ = $this->qs;
            foreach ($this->binds as $bl => $value) {
                if (is_numeric($value)) $tsQ = str_replace($bl, $value, $tsQ);
                else $tsQ = str_replace($bl, "'" . $value . "'", $tsQ);
            }
            $retString .= "<br>Full Query: [" . $tsQ . "]";
        } else {
            $retString .= "no Params";
        }
        return $retString;
    }
}
