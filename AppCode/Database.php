<?php

class Database {

    private $sHost = "localhost";
    private $sUsername = "root";
    private $sPassword = "";
    private $sDatabaseName = "sinch_recruit_management";
    
    private $objConnection;
    public $RecordSet;
    public $RecordSetAssociated;

   //Connects to the database
    function ConnectToDatabase() {
        $selectedDatabase = NULL;
        $this->objConnection = mysqli_connect($this->sHost, $this->sUsername, $this->sPassword, $this->sDatabaseName);

        //Checks the db connectivity
        if ($this->objConnection === false) {
            throw new Exception("Database server down.");
        }
        $selectedDatabase = mysqli_set_charset($this->objConnection, 'utf8');

        //Checks the db connectivity
        if ($selectedDatabase === false) {
            throw new Exception("Provided database is not accessible.");
        }

        return $this->objConnection;
    }

    //Disconnects from the database
    function DisconnectFromDatabase() {
        mysqli_close($this->objConnection);
    }

    //Inserts records into the table
    function InsertToTable() {
        $sQuery = "INSERT INTO $this->TableName (";

        foreach ($this->Data as $k => $v) {
            $arrColumnsName[$k] = "$k";
        }
        if (count($arrColumnsName) > 0) {
            $sQuery .= implode(",", $arrColumnsName);
        }

        $sQuery = $sQuery . ") VALUES(";

        foreach ($this->Data as $k => $v) {
            if ($v == "NULL")
                $arr[$k] = "$v";
            else
                $arr[$k] = "'$v'";
        }
        if (count($arr) > 0) {
            $sQuery .= implode(",", $arr);
        }

        $sQuery = $sQuery . ")";

        $this->Execute($sQuery);
        //return $sQuery;

        return $this->AffectedRowID;
    }

    //Updates records to the table
    function UpdateToTable() {
        $query = "UPDATE $this->TableName SET ";

        foreach ($this->Data as $key => $value) {
            if ($value == "NULL")
                $arr[$key] = "$key=$value";
            else
                $arr[$key] = "$key='$value'";
        }
        if (count($arr) > 0) {
            $query .= implode(",", $arr);
        }

        foreach ($this->Conditions as $key => $value) {
            if ($value == "NULL")
                $val[$key] = "$key=$value";
            else
                $val[$key] = "$key='$value'";
        }
        if (count($val) > 0) {
            $query .= " WHERE " . implode(" and ", $val);
        }

        return $this->Execute($query);
        //return $query;
    }

    //Deletes record from the table
    function DeleteFromTable() {
        $query = "DELETE FROM $this->TableName ";

        foreach ($this->Conditions as $key => $val) {
            $value[$key] = "$key='$val'";
        }
        if (count($value) > 0) {
            $query .= " WHERE " . implode(" and ", $value);
        }

        $this->Execute($query);
        //echo $query;
    }

    public function FetchArray($RecordSet) {
        return mysqli_fetch_array($RecordSet);
    }

    //Executes the given SQL. Mostly DMLs.
    public function Execute($Sql) {
        $this->ConnectToDatabase();
        $objResult = mysqli_query($this->objConnection, $Sql);
        if ($objResult === false)
            throw new Exception(mysqli_error($this->objConnection));
        $this->AffectedRowID = mysqli_insert_id($this->objConnection);
        $this->DisconnectFromDatabase();
        if ($objResult)
            return $objResult;
    }

}

?>
