<?php
session_start();
include('AppCode/SinchRecruitManagement.php');
$ACM = new SinchRecruitManagement();
?>

<!-- Navigation -->
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="css/custom.css" rel="stylesheet" type="text/css"/>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="images/logo.png" class="logo w3-round" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse justify-content-end navbar-collapse" id="navbarResponsive">
            <ul class="nav nav-pills">
                <?php if ($_SESSION['user']['role_name'] != SinchRecruitManagement::USER_ROLE_USER) { ?>
                    <li class="nav-item">
                        <a class="menu-dashboard nav-link active" href="index.php">Dashboard</a>
                    </li>
                <?php } else if ($_SESSION['user']['role_name'] == SinchRecruitManagement::USER_ROLE_USER) { ?>
                    <li class="nav-item">
                        <a class="menu-apply-job nav-link" href="applyJob.php">Apply Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="menu-edit-profile nav-link" href="editProfile.php">Edit Profile</a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="menu-logout nav-link" href="login.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
