<?php

include('../AppCode/SinchRecruitManagement.php');
$ACM = new SinchRecruitManagement();

if (isset($_POST['status']) && !empty($_POST['status'])) {
    $update_data['id'] = $_POST['user_id'];
    $update_data['status'] = $_POST['status'];
    $result = $ACM->UpdateUser($update_data);
} else {
    //To remove user profile and sign photo
    $user_details = $ACM->GetUsers($_POST['user_id']);
    if (!empty($user_details['profile_picture']) && file_exists(SinchRecruitManagement::PUBLIC_PATH . SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH . $user_details['profile_picture'])) {
        unlink(SinchRecruitManagement::PUBLIC_PATH . SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH . $user_details['profile_picture']);
    }
    if (!empty($user_details['signature']) && file_exists(SinchRecruitManagement::PUBLIC_PATH . SinchRecruitManagement::USER_SIGN_UPLOAD_PATH . $user_details['signature'])) {
        unlink(SinchRecruitManagement::PUBLIC_PATH . SinchRecruitManagement::USER_SIGN_UPLOAD_PATH . $user_details['signature']);
    }

    $result = $ACM->RemoveUser($_POST['user_id']);
}
if ($result) {
    echo 1;
} else {
    echo 0;
}
exit();
?>