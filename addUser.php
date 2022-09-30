<?php
require_once 'header.php';

if (isset($_POST['btn-add-user'])) {
    //To create new user
    $new_user = array();
    $new_user['name'] = $_POST['name'];
    $new_user['email'] = $_POST['email'];
    $new_user['mobile'] = $_POST['mobile'];
    $new_user['password'] = md5($_POST['pswd']);
    if (!empty($_POST['address']))
        $new_user['address'] = $_POST['address'];
    $new_user['gender'] = $_POST['gender'];
    $new_user['dob'] = $_POST['dob'];
    $new_user['status'] = SinchRecruitManagement::USER_STATUS_ACTIVE;

    //To get role id
    $role_detail = $ACM->GetRoles('', $_POST['role_name']);
    $new_user['role_id'] = $role_detail['id'];

    //If profile pictures uploaded
    // Check if image file is a actual image or fake image
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
        $_SESSION['success'] = 'User added successfully';
    } else {
        $_SESSION['err'] = 'Something went wrong while add user..! Please try again..!';
    }

    $ACM->RedirectPage('index.php');
}
?>
<div class="container">
    <div class="row">
        <div id="bdy-edit-prof">
            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
                <form autocomplete="off" action="#" id="form-register-user" method="POST" enctype="multipart/form-data">
                    <label for="chk" class="login-label" aria-hidden="true">Add User</label>
                    <input type="text" name="name" placeholder="Name" required="">
                    <input type="text" name="mobile" placeholder="Mobile" required="">
                    <input type="email" name="email" placeholder="Email" required="">
                    <input type="password" name="pswd" placeholder="Password" required="">
                    <textarea name="address" placeholder="Address"></textarea>
                    <label class="radio-label">User Role</label>
                    <select id="role_name" name="role_name">
                        <option value="<?= SinchRecruitManagement::USER_ROLE_SUPER_ADMIN ?>">Super Admin</option>
                        <option value="<?= SinchRecruitManagement::USER_ROLE_ADMIN ?>">Admin</option>
                        <option value="<?= SinchRecruitManagement::USER_ROLE_USER ?>" selected="selected">User</option>
                    </select><br>
                    <label class="radio-label">Gender:</label><br>
                    <label class="radio-label">Male</label> <input type="radio" checked="checked" name="gender" value="male">
                    <label class="radio-label">Female</label> <input type="radio" name="gender" value="female">
                    <label class="radio-label">Other</label> <input type="radio" name="gender" value="other">
                    <input type="date" name="dob" class="dob" placeholder="DOB" value="<?= date('Y-m-d', strtotime("-18 year", time())); ?>">
                    <label class="radio-label">Profile Picture</label>
                    <input type="file" name="profile_picture">
                    <small class="ml-7 txt-shadow">Upload profile picture with jpg/jpeg/png format</small>
                    <label class="radio-label mt-1">Signature</label>
                    <input type="file" name="signature">
                    <small class="ml-7 txt-shadow">Upload signature picture with jpg/jpeg/png format</small>
                    <button type = "submit" name = "btn-add-user" id = "btn-add-user">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>