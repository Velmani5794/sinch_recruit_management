<?php

require_once 'Database.php';

class SinchRecruitManagement {

    const USER_ROLE_SUPER_ADMIN = 'Super Admin';
    const USER_ROLE_ADMIN = 'Admin';
    const USER_ROLE_USER = 'User';
    const USER_STATUS_ACTIVE = 'active';
    const USER_STATUS_INACTIVE = 'inactive';
    const USER_PROF_PIC_UPLOAD_PATH = "uploads/profile_picture/";
    const USER_SIGN_UPLOAD_PATH = "uploads/sign/";
    const PUBLIC_PATH = "C:/xampp/htdocs/SinchRecruitManagement/";

    //Constructor of the class
    function _construct() {
        
    }

//Redirects to the URL given in the input parameter. 
    function RedirectPage($url) {

//Checks if headers are already sent
        if (headers_sent()) {
            echo "<script language=\"Javascript\">window.location.href='$url';</script>";
            exit;
        } else {
            session_write_close();
            header("Location:$url");
            exit;
        }
    }

//Generic function which converts a MYSQL Result into a PHP Array object
    function LoadBusinessObject($DBResult) {

        $database = new Database;
        $objectArrayToBeReturned = Array();

        while ($record = $database->FetchArray($DBResult)) {

            $objectToBeReturned = Array();
            foreach ($record as $key => $value) {
                $objectToBeReturned[$key] = "$value";
            }
            array_push($objectArrayToBeReturned, $objectToBeReturned);
        }
        return $objectArrayToBeReturned;
    }

//Get Users Table
    function GetUsers($UserID = "", $email = "", $pswd = "") {
        $database = new Database;
        if ($UserID != "") {
            $query = "SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id WHERE u.id=" . $UserID;
        } else if (!empty($email) && !empty($pswd)) {
            $query = "SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id WHERE u.email='" . $email . "' AND u.password='" . md5($pswd) . "'";
        } else {
            $query = "SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id ORDER BY u.id DESC";
        }
        $result = $database->Execute($query);
        $user = $this->LoadBusinessObject($result);
        if ($UserID != "" || (!empty($email) && !empty($pswd))) {
            return $user[0];
        } else {
            return $user;
        }
    }

//Add a user to the system
    function AddUser($user) {
        $database = new Database;
        $database->TableName = "users";
        $database->Data = $user;
        return $database->InsertToTable();
    }

//Update user table
    function UpdateUser($User) {
        $database = new Database;
        $database->TableName = "users";
        $database->Conditions["id"] = $User["id"];
        $database->Data = $User;
        return $database->UpdateToTable();
    }

//Delete function for user
    function RemoveUser($UserID) {
        $database = new Database;
        $database->TableName = "users";
        $database->Conditions["id"] = $UserID;
        $database->DeleteFromTable();
        return true;
    }

    //Get roles Table
    function GetRoles($RoleID = "", $role_name = "") {
        $database = new Database;
        if ($RoleID != "") {
            $query = "SELECT * FROM roles WHERE id=" . $RoleID;
        } else if ($role_name != "") {
            $query = "SELECT * FROM roles WHERE role_name='" . $role_name . "'";
        } else {
            $query = "SELECT * FROM roles ORDER BY u.id DESC";
        }
        $result = $database->Execute($query);
        $role = $this->LoadBusinessObject($result);
        if ($RoleID != "" || $role_name != '') {
            return $role[0];
        } else {
            return $role;
        }
    }

    function upload_img($upload_file, $target_dir) {
        $uploadOk = 1;
        $err = '';
        $imageFileType = strtolower(pathinfo(basename($upload_file["name"]), PATHINFO_EXTENSION));
        $file_name = time() . uniqid(rand()) . "." . $imageFileType;
        $target_file = $target_dir . $file_name;

        $check = getimagesize($upload_file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $err = "File is not an image.";
            $uploadOk = 0;
        }

// Check if file already exists
        if (file_exists($target_file)) {
            $err = "Sorry, file already exists.";
            $uploadOk = 0;
        }

// Check file size
        if ($upload_file["size"] > 200000) {
            $err = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $err = "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }

// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $err = "Sorry, your file was not uploaded.";
            return array('err' => $err, 'code' => 0);
// if everything is ok, try to upload file
        } else {
            if (!move_uploaded_file($upload_file["tmp_name"], $target_file)) {
                $err = "Sorry, there was an error uploading your file.";
                return array('err' => $err, 'code' => 0);
            } else {
                return $file_name;
            }
        }
    }

}

?>