<?php
$v='1.2';

$user_img = $this->user->getProfilePicturePath($_SESSION['user']['id_user'])
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" /> 
    
    <title><?=$this->getTitle();?></title>
    <link rel="shortcut icon" href="<?=$this->getBaseUrl()?>img/favicon.ico" type="image/x-icon"> 

    <link href="<?=$this->getBaseUrl()?>css/icons.css" rel="stylesheet">
    <link href="<?=$this->getBaseUrl()?>css/style.css?v=<?=$v?>" rel="stylesheet">
    <link href="<?=$this->getBaseUrl()?>css/custom.css?v=<?=$v?>" rel="stylesheet">
    <link href="<?=$this->getBaseUrl()?>css/darkmode.css" rel="stylesheet">

    <link rel="manifest" href="<?=$this->getBaseUrl()?>manifest.json">

    <script src="<?=$this->getBaseUrl()?>js/jquery-3.7.1.min.js"></script>
    <script src="<?=$this->getBaseUrl()?>js/popper.min.js"></script>
    <script src="<?=$this->getBaseUrl()?>js/bootstrap.min.js"></script>
    <script src="<?=$this->getBaseUrl()?>js/mybook.js?v=<?=$v?>"></script>

    <script src="<?=$this->getBaseUrl()?>js/plugins/validate/jquery.validate.min.js?v=<?= $v ?>"></script>

    <base href="<?=$this->getBaseUrl()?>">
</head>

<body class="fixed-sidebar6 <?=($_SESSION['user']['menu'])?'mini-navbar':''?> <?=($_SESSION['user']['mode'])?'dark-mode':''?>">


    <nav class="navbar header-navbar fixed-top">
        <div class="navbar-left">
            <a href="#" class="menu-button d-xs-block d-sm-block">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1"></rect>
                    <rect x="0.5" y="7.5" width="25" height="1"></rect>
                    <rect x="0.5" y="15.5" width="25" height="1"></rect>
                </svg>
            </a>
            <div class="search" data-search-path="Pages.Search.html?q=">
                <input placeholder="Search..." /> <span class="search-icon"><i class="simple-icon-magnifier"></i></span>
            </div>
        </div>

        <a class="navbar-logo" href="Dashboard.Default.html">
            <span class="logo d-none d-xs-block"></span> 
            <span class="logo-mobile d-block d-xs-none"></span>
        </a>

        <div class="navbar-right">
            <div class="user d-inline-block">
                <button class="btn btn-empty p-0 d-flex align-items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="name"><?=$_SESSION['user']['first_name'] . ' '. $_SESSION['user']['last_name'] ?></span> 
                    <span>
                        <img alt="Profile Picture" src="<?= $user_img ?>" />
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3">
                    <a class="dropdown-item" href="#">Account</a> 
                    <a class="dropdown-item" href="#" id="change-profile">Change profile picture</a>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="image" id="profile-input" class="hidden">
                        <input type="hidden" name="update_profile" value="1">
                    </form>
                    <a class="dropdown-item change-mode" data-mode="<?=$_SESSION['user']['mode']?>"><div class="inline"><?=$_SESSION['user']['mode']?'Light mode':'Dark mode'?></div></a>
                    <a class="dropdown-item" data-toggle="modal" data-target="#modal" data-form="#update_password_form" >Update my password</a>
                    <a class="dropdown-item" href="index.php?t1=login&t2=logout">Sign out</a>
                </div>
            </div>
        </div>
    </nav>

    <div id="sidebar_menu" class="menu">
        <div class="main-menu default-transition">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="<?=($t1=='home' )?'active':''?>">
                        <a href="index.php?t1=home">
                            <i class="svg-icon svg-dashboard"></i>
                            <span>Dashboards</span>
                        </a>
                    </li>
                    <li class="<?=($t1=='passbook' && $t2=='default')?'active':''?>">
                        <a href="index.php?t1=passbook&t2=default">
                            <i class="svg-icon svg-budget"></i> Passbook
                        </a>
                    </li>
                    <?php if($this->isAdmin()): ?>
                        <li class="<?=($t1=='passbook' && $t2=='category')?'active':''?>">
                            <a href="index.php?t1=passbook&t2=category">
                                <i class="svg-icon svg-category"></i> Category
                            </a>
                        </li>
                        <li class="<?=($t1=='weight' && $t2=='default')?'active':''?>">
                            <a href="index.php?t1=weight&t2=default">
                                <i class="svg-icon svg-weight-scale"></i> Weight
                            </a>
                        </li>
                        <li class="<?=($t1=='user' )?'active':''?>">
                            <a href="index.php?t1=user&t2=search">
                                <i class="svg-icon svg-users"></i> Users
                            </a>
                        </li>
                        <li class="<?=($t1=='advanced')?'active':''?>">
                            <a href="index.php?t1=advanced&t2=options">
                                <i class="svg-icon svg-options"></i> Options
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <main style="opacity: 1;" class="default-transition">
        <div class="container-fluid">
            <div class="row" id="notifications_alert">
                <?php foreach (alert_shift() as $type => $value): ?>
                    <?php if($value): ?>
                        <div class="col-lg-12 m-t-md m-b-md">
                            <div class="alert alert-<?=$type?> m-b-none m-l-sm"><?=$value?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>