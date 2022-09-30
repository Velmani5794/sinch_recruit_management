<?php

include('AppCode/SinchRecruitManagement.php');
$ACM = new SinchRecruitManagement();

$username = $_POST['email'];
$password = $_POST['pswd'];

session_start();

//to prevent from mysqli injection  
$username = stripcslashes($username);
$password = stripcslashes($password);

$user = $ACM->GetUsers("", $username, $password);

if (count($user) > 0) {
    if ($user['status'] == SinchRecruitManagement::USER_STATUS_ACTIVE) {
        $_SESSION['user'] = $user;
        $redirect_page = ($_SESSION['user']['role_name'] == SinchRecruitManagement::USER_ROLE_USER) ? 'applyJob.php' : 'index.php';
        $ACM->RedirectPage($redirect_page);
    } else {
        $_SESSION['err'] = 'Sorry..! Profile has been deactivated!';
        $ACM->RedirectPage('login.php');
    }
} else {
    $_SESSION['err'] = 'Invalid username and password';
    $ACM->RedirectPage('login.php');
}
?> 