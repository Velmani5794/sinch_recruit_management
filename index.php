<?php
require_once 'header.php';
?>
<div class="container mt-10 mb-10">
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
    <div class="alert-success" style="display: none;">
        <span class="closebtn" onclick="this.parentElement.style.display = 'none';">&times;</span> 
        <strong class="test"></strong>
    </div>
    <h1>
        Users
        <a href="addUser.php" class="btn action-btn btn-sm btn-primary" title="Add User"><i class="fa fa-plus text-white"></i></a>
    </h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Profile Picture</th>
                <th>Action</th>
        </thead>
        <tbody id="tbdyUser">

        </tbody>
    </table>
</div>

<script type="text/javascript" language="javascript">
    $(document).ready(function () {

        var loginuser_role = "<?= $_SESSION['user']['role_name']; ?>";
        var loginuser_id = "<?= $_SESSION['user']['id']; ?>";

        $.ajax({
            type: 'GET',
            url: "ajax/fetchUserData.php",
            success: function (data)
            {
                var response = jQuery.parseJSON(data);
                if (response.success == 1) {
                    $.each(response.user_list, function (key, user) {

                        var activebtn = '';
                        var editbtn = '';
                        var deletebtn = '';
                        if (user.role_name == "<?= SinchRecruitManagement::USER_ROLE_USER; ?>" && user.status == "<?= SinchRecruitManagement::USER_STATUS_ACTIVE; ?>") {
                            activebtn = '<a href="#" onclick="approvalConfirmation(\'inactive\', ' + user.id + ')" id="active" user_id="' + user.id + '" class="btn-active-inactive' + user.id + ' btn-active-inactive action-btn btn btn-sm btn-warning" title="Deactivate"><i class="fa fa-times-circle text-white"></i></a>';
                        } else if (user.role_name == "<?= SinchRecruitManagement::USER_ROLE_USER; ?>") {
                            activebtn = '<a href="#" onclick="approvalConfirmation(\'active\', ' + user.id + ')" id="inactive" user_id="' + user.id + '" class="btn-active-inactive' + user.id + ' action-btn btn btn-sm btn-info" title="Activate"><i class="fa fa-check-circle text-white"></i></a>';
                        }

                        if (loginuser_role == "<?= SinchRecruitManagement::USER_ROLE_SUPER_ADMIN; ?>") {
                            editbtn = '<a href="editProfile.php?uid=' + user.id + '" class="btn action-btn btn-sm btn-success" title="Edit User"><i class="fa fa-pencil text-white"></i></a>';
                        }

                        var profpic = "";
                        if (user.profile_picture != "") {
                            profpic = "<img class='pro-img' src='<?= SinchRecruitManagement::USER_PROF_PIC_UPLOAD_PATH; ?>" + user.profile_picture + "' alt='Profile Picture' />";
                        }
                        if (loginuser_id != user.id) {
                            deletebtn = '<a href="#" class="btn action-btn btn-sm btn-danger" onclick="approvalConfirmation(\'\', ' + user.id + ')" title="Delete User"><i class="fa fa-trash text-white"></i></a>';
                        }
                        var tdUser = "<tr id='usr" + user.id + "'>" +
                                "<td>" + user.name + "</td>" +
                                "<td>" + user.role_name + "</td>" +
                                "<td>" + user.mobile + "</td>" +
                                "<td>" + user.email + "</td>" +
                                "<td>" + user.gender + "</td>" +
                                "<td>" + user.dob + "</td>" +
                                "<td>" + profpic + "</td>" +
                                "<td>" + editbtn + activebtn + deletebtn +
                                "</td>" +
                                "</tr>";
                        $('#tbdyUser').append(tdUser);
                    });
                } else {
                    console.log('Something went wrong..! Please try agan..!');
                }
            }
        }
        );
    });
    function approvalConfirmation(status = '', user_id) {
        var msg = (status == '') ? "Are you sure to delete this user?" : "Are you sure to active/deactive this user?";
        var errmsg = (status == '') ? "delete" : "activate/deactivate";
        var successmsg = (status == '') ? "deleted" : "activated/deactivated";
        if (confirm(msg) == true) {
            $.ajax({
                type: 'POST',
                url: "ajax/userStatusUpdate.php",
                data: {status: status, user_id: user_id},
                success: function (data, textStatus, jqXHR)
                {
                    if (data != 1) {
                        $('.success-err-msg').html('Something went wrong while ' + errmsg + ' user..! Please try again later..!');
                        $('.success-err-msg').show();
                    } else {
                        if (status == '') {
                            $('#usr' + user_id).remove();
                        } else {
                            if (status == "<?= SinchRecruitManagement::USER_STATUS_ACTIVE ?>") {
                                $('.btn-active-inactive' + user_id).replaceWith('<a href="#" onclick="approvalConfirmation(\'inactive\', ' + user_id + ')" id="active" user_id="' + user_id + '" class="btn-active-inactive' + user_id + ' btn-active-inactive btn btn-sm btn-warning" title="Deactivate"><i class="fa fa-times-circle text-white"></i></a>');
                            } else if (status == "<?= SinchRecruitManagement::USER_STATUS_INACTIVE ?>") {
                                $('.btn-active-inactive' + user_id).replaceWith('<a href="#" onclick="approvalConfirmation(\'active\', ' + user_id + ')" id="inactive" user_id="' + user_id + '" class="btn-active-inactive' + user_id + ' btn-active-inactive btn btn-sm btn-info" title="Activate"><i class="fa fa-check-circle text-white"></i></a>');
                            }
                        }

                        $('.test').html('User ' + successmsg + ' successfully');
                        $('.alert-success').show();
                        setTimeout(function () {
                            $('.alert-success').fadeOut('fast');
                        }, 3000);

                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert("error");
                }
            });
        } else {
            return false;
    }
    }
</script>
<?php require_once 'footer.php'; ?>