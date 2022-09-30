<?php

include('../AppCode/SinchRecruitManagement.php');
$ACM = new SinchRecruitManagement();

$users = $ACM->GetUsers();

$result = array();
if (count($users) > 0) {
    $result['user_list'] = $users;
    $result['success'] = 1;
} else {
    $result['success'] = 0;
}
echo json_encode($result);
?>