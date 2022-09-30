<?php
require_once 'header.php';

$user_id = $_SESSION['user']['id'];
if ($_SESSION['user']['role_name'] == SinchRecruitManagement::USER_ROLE_SUPER_ADMIN)
    $user_id = $_GET['uid'];


$user_details = $ACM->GetUsers($user_id);

if (isset($_POST['btn-edit-profile'])) {
    //To update user details
    $update_user = array();
    $update_user['name'] = $_POST['name'];
    $update_user['mobile'] = $_POST['mobile'];
    if (!empty($_POST['pswd']))
        $update_user['password'] = md5($_POST['pswd']);
    if (!empty($_POST['address']))
        $update_user['address'] = $_POST['address'];
    $update_user['gender'] = $_POST['gender'];
    $update_user['dob'] = $_POST['dob'];
    $update_user['status'] = ($_SESSION['user']['role_name'] == SinchRecruitManagement::USER_ROLE_SUPER_ADMIN) ? SinchRecruitManagement::USER_STATUS_ACTIVE : SinchRecruitManagement::USER_STATUS_INACTIVE;

    //If profile pictures uploaded
    // Check if image file is a actual image or fake image
    if ($_FILES['profile_picture']['size'] != 0) {
        $prof_pic = $ACM->upload_img($_FILES["profile_picture"], SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH);
        $update_user['profile_picture'] = (!is_array($prof_pic)) ? $prof_pic : $_POST['hidProfPic'];
        (!is_array($prof_pic)) ? (unlink(SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH . $_POST['hidProfPic'])) : '';
    }
    if ($_FILES['signature']['size'] != 0) {
        $sign = $ACM->upload_img($_FILES["signature"], SinchRecruitManagement::USER_SIGN_UPLOAD_PATH);
        $update_user['signature'] = (!is_array($sign)) ? $sign : $_POST['hidSign'];
        (!is_array($sign)) ? (unlink(SinchRecruitManagement::USER_SIGN_UPLOAD_PATH . $_POST['hidSign'])) : '';
    }
    $update_user['id'] = $user_id;
    if ($ACM->UpdateUser($update_user)) {
        $_SESSION['success'] = 'User updated successfully';
    } else {
        $_SESSION['err'] = 'Something went wrong while update user..! Please try again..!';
    }
    if ($_SESSION['user']['role_name'] == SinchRecruitManagement::USER_ROLE_SUPER_ADMIN) {
        $ACM->RedirectPage('index.php');
    } else {
        $ACM->RedirectPage('editProfile.php');
    }
}
?>
<div class="container">
    <div class="row">
        <div id="bdy-edit-prof">
            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
                <form autocomplete="off" action="#" id="form-register-user" method="POST" enctype="multipart/form-data">
                    <label for="chk" class="login-label" aria-hidden="true">Edit Profile</label>
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
                    <input type="text" name="name" placeholder="Name" required="" value="<?= $user_details['name']; ?>">
                    <input type="text" name="mobile" placeholder="Mobile" required="" value="<?= $user_details['mobile']; ?>">
                    <input type="email" name="email" disabled="disabled" placeholder="Email" required="" value="<?= $user_details['email']; ?>">
                    <input type="password" name="pswd" placeholder="Password">
                    <textarea name="address" placeholder="Address"><?= $user_details['address']; ?></textarea>
                    <label class="radio-label">Gender:</label><br>
                    <label class="radio-label">Male</label> <input type="radio" checked="checked" name="gender" <?php echo ($user_details['gender'] == 'male') ? "checked" : ""; ?> value="male">
                    <label class="radio-label">Female</label> <input type="radio" name="gender" value="female" <?php echo ($user_details['gender'] == 'female') ? "checked" : ""; ?>>
                    <label class="radio-label">Other</label> <input type="radio" name="gender" value="other" <?php echo ($user_details['gender'] == 'other') ? "checked" : ""; ?>>
                    <input type="date" name="dob" class="dob" placeholder="DOB" value="<?= $user_details['dob']; ?>">
                    <label class="radio-label">Profile Picture</label>
                    <input type="file" name="profile_picture">
                    <small class="ml-7 txt-shadow">Upload profile picture with jpg/jpeg/png format</small>
                    <label class="radio-label mt-1">Signature</label>
                    <input type="file" name="signature">
                    <small class="ml-7 txt-shadow">Upload signature picture with jpg/jpeg/png format</small>
                    <input type="hidden" name="hidProfPic" value="<?= $user_details['profile_picture']; ?>"/>
                    <input type="hidden" name="hidSign" value="<?= $user_details['signature']; ?>" />
                    <button type = "submit" name = "btn-edit-profile" id = "btn-edit-profile">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type = "text/javascript" language = "javascript">
    $(document).ready(function () {
        $('.nav-link').removeClass('active');
        $('.menu-edit-profile').addClass('active');
    });
</script>