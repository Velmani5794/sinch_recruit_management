<?php
session_start();
include('AppCode/SinchRecruitManagement.php');
$ACM = new SinchRecruitManagement();

if (isset($_POST['btn-register-user'])) {
    //To get user role id
    $user_role = $ACM->GetRoles("", SinchRecruitManagement::USER_ROLE_USER);
    $new_user = array();
    $new_user['name'] = $_POST['name'];
    $new_user['role_id'] = $user_role['id'];
    $new_user['mobile'] = $_POST['mobile'];
    $new_user['email'] = $_POST['email'];
    $new_user['password'] = md5($_POST['pswd']);
    $new_user['address'] = $_POST['address'];
    $new_user['gender'] = $_POST['gender'];
    $new_user['dob'] = $_POST['dob'];

    //If profile pictures uploaded
    // Check if image file is a actual image or fake image
    $new_user['profile_picture'] = $new_user['signature'] = '';
    if ($_FILES['profile_picture']['size'] != 0) {
        $prof_pic = $ACM->upload_img($_FILES["profile_picture"], SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH);
        $new_user['profile_picture'] = (!is_array($prof_pic)) ? $prof_pic : '';
    }
    if ($_FILES['signature']['size'] != 0) {
        $sign = $ACM->upload_img($_FILES["signature"], SinchRecruitManagement::USER_SIGN_UPLOAD_PATH);
        $new_user['signature'] = (!is_array($sign)) ? $sign : '';
    }
    $userID = $ACM->AddUser($new_user);
    if ($userID > 0) {
        $_SESSION['success'] = 'Sinch account created successfully';
    } else {
        $_SESSION['err'] = 'Something went wrong while register your application..! Please try again..!';
    }
    $ACM->RedirectPage('login.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="slide navbar style.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>

        <script src="js/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="main">  	
            <input type="checkbox" id="chk" aria-hidden="true">

            <div class="login">
                <form name="f1" action = "authentication.php" method = "POST">  
                    <label for="chk" class="login-label" aria-hidden="true">Login</label>
                    <img src="images/login-logo.png" alt="Logo" class="login-logo" /><br>
                    <?php
                    if ((isset($_SESSION['err']) && !empty($_SESSION['err'])) || (isset($_SESSION['success']) && !empty($_SESSION['success']))) {
                        $msg = (isset($_SESSION['err']) && !empty($_SESSION['err'])) ? $_SESSION['err'] : $_SESSION['success'];
                        $alert_class = (isset($_SESSION['err']) && !empty($_SESSION['err'])) ? 'alert' : 'alert-success';
                        ?>
                        <div class="<?= $alert_class; ?>">
                            <span class="closebtn" onclick="this.parentElement.style.display = 'none';">&times;</span> 
                            <strong><?= $msg; ?></strong>
                        </div>
                        <?php
                    }
                    unset($_SESSION['err']);
                    unset($_SESSION['success']);
                    ?>
                    <input type="email" name="email" id="email" placeholder="Email" >
                    <small class="spn-email-err txt-err d-none">Email is required</small>
                    <input type="password" name="pswd" id="pswd" placeholder="Password">
                    <small class="spn-pswd-err txt-err d-none">Password is required</small>
                    <button id="login">Login</button>
                </form>
            </div>

            <div class="signup">
                <form autocomplete="off" action="#" id="form-register-user" method="POST" enctype="multipart/form-data">
                    <label for="chk" class="login-label" aria-hidden="true">Register</label>
                    <input type="text" name="name" placeholder="Name *" required="">
                    <input type="text" name="mobile" placeholder="Mobile *" required="">
                    <input type="email" name="email" placeholder="Email *" required="" autocomplete="off">
                    <input type="password" name="pswd" placeholder="Password *" required="">
                    <textarea name="address" placeholder="Address"></textarea>
                    <label class="radio-label">Gender:</label><br>
                    <label class="radio-label">Male</label> <input type="radio" checked="checked" name="gender" value="male">
                    <label class="radio-label">Female</label> <input type="radio" name="gender" value="female">
                    <label class="radio-label">Other</label> <input type="radio" name="gender" value="other">
                    <label class="radio-label">DOB:</label><input type="date" name="dob" class="dob" placeholder="DOB" value="<?= date('Y-m-d', strtotime("-18 year", time())); ?>">
                    <label class="radio-label">Profile Picture</label>
                    <input type="file" name="profile_picture">
                    <label class="radio-label">Signature</label>
                    <input type="file" name="signature">
                    <button type="submit" name="btn-register-user" id="btn-register-user">Sign up</button>
                </form>
            </div>
        </div>
        <script type="text/javascript" language="javascript">
            $(document).ready(function () {

                $('#login').click(function () {
                    var email = $('#email').val();
                    var pswd = $('#pswd').val();
                    var err = false;

                    if (email == '' || typeof email == 'undefined') {
                        $('.spn-email-err').show();
                        var err = true;
                    } else {
                        $('.spn-email-err').hide();
                    }

                    if (pswd == '' || typeof pswd == 'undefined') {
                        $('.spn-pswd-err').show();
                        var err = true;
                    } else {
                        $('.spn-pswd-err').hide();
                    }

                    if (err) {
                        return false;
                    }
                });
            });
        </script>  
    </body>
</html>