<?php
$v='1.0';
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

        <script src="<?=$this->getBaseUrl()?>js/jquery-3.1.1.min.js"></script>
        <script src="<?=$this->getBaseUrl()?>js/bootstrap.min.js"></script>
        <script src="<?=$this->getBaseUrl()?>js/mybook.js?v=<?=$v?>"></script>

        <script src="js/plugins/validate/jquery.validate.min.js?v=<?= $v ?>"></script>

        <base href="<?=$this->getBaseUrl()?>">
    </head>

    <style type="text/css">
    body {
        position: relative;
        margin: 0;
        min-height: 100vh;
    }

    .screen-bg-wrap {
        position: absolute;
        filter: blur(140px);
        /*z-index: -1;*/
        inset: 0px;
        overflow: hidden;
    }
    .screen-bg-wrap::before {
        content: " ";
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background-color: rgb(247, 220, 179);
        position: absolute;
        top: 0px;
        right: 0px;
        opacity: 1;
    }
    .screen-bg-item-1 {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background-color: rgb(192, 229, 217);
        margin-left: 160px;
        position: absolute;
        bottom: 180px;
        opacity: 1;
    }
    .screen-bg-item-2 {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background-color: rgb(231, 103, 103);
        position: absolute;
        bottom: 0px;
        left: -50px;
        opacity: 1;
    }

    .authentication-wrap {
        box-sizing: border-box;
        display: flex;
        flex-flow: wrap;
        width: 100vw;
        height: 100vh;
        margin: 0px;
        flex-basis: 100%;
        flex-grow: 0;
        max-width: 100%;
        justify-content: center;
        align-items: center;
        padding: 15px;
    }
    .middle-box {
        background-color: rgb(255, 255, 255);
        color: rgb(29, 38, 48);
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        box-shadow: none;
        position: relative;
        overflow: inherit;
        border: 1px solid rgba(219, 224, 229, 0.65);
        border-radius: 12px;
        width: 100%;
        padding: 40px;
    }

    .middle-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
    }
    .middle-box-header .middle-box-title {
        margin: 0;
        line-height: 1;
        font-size: 24px;
        font-weight: 700;
    }

    .form-row {
        margin-bottom: 20px;
    }
    .form-row:last-child {
        margin-bottom: 0px;
    }
    .form-row .form-label {
        font-size: 14px;
        line-height: 1.2;
        font-weight: 400;
        padding: 0px;
        position: relative;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        margin-bottom: 8px;
        transition: color 200ms cubic-bezier(0, 0, 0.2, 1) 0ms, transform 200ms cubic-bezier(0, 0, 0.2, 1) 0ms, max-width 200ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        color: rgb(91, 107, 121);
    }
    .form-row .form-control {
        font-size: 14px;
        line-height: 1.4;
        font-weight: 400;
        color: rgb(29, 38, 48);
        box-sizing: border-box;
        cursor: text;
        display: inline-flex;
        align-items: center;
        width: 100%;
        position: relative;
        border-radius: 8px;
        padding: 14px;
    }

    .remember-me-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .remember-me-box .remember-me {
        display: flex;
        align-items: center;
    }
    .remember-me-box .remember-me h6 {
        margin: 0;
        padding-left: 5px;
    }
    .btn.full-width {
        width: 100%;
        margin-top: 30px;
        cursor: pointer;
    }
    .toggle-password {
        margin-top: -34px;
        z-index: 999;
        position: relative;
    }

    @media (min-width: 1024px) {
        .middle-box {
            max-width: 480px;
            margin: 24px;
        }
    }
    </style>

    <body>
        <div class="screen-bg-wrap">
            <div class="screen-bg screen-bg-item-1"></div>
            <div class="screen-bg screen-bg-item-2"></div>
        </div>

        <div class="authentication-wrap">
            <div class="middle-box">
                <div class="middle-box-header">
                    <h3 class="middle-box-title">Login</h3>
                    <a class="middle-box-link" href="#">Don't have an account?</a>
                </div>
                <div class="middle-box-body">
                    <form method="post" action="" id="login_form" class="connect-form">
                        <div class="form-row">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="login" class="form-control" placeholder="Email" required="required">
                        </div>

                        <div class="form-row">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" id="password" class="form-control password-field" placeholder="Password" required="required">
                            <i class="toggle-password svg-icon svg-eye-close"></i>
                        </div>

                        <div class="remember-me-box">
                            <label class="remember-me">
                                <input type="checkbox" name="remember" class="align-middle m-r-xs">
                                <h6 class="">Keep me sign in</h6>
                            </label>
                            <a href="index.php?t1=login&t2=forgot">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary full-width m-b">Login</button>

                        <?php if($data['validationStatus'] == false): ?>
                            <div class="text-validation m-b">
                                <b>Email address or password is incorrect</b><br />
                            </div>
                        <?php endif;?>
                    </form>
                </div>

                <!-- Place this in your HTML file where you want the install button to appear -->
                <button id="installButton" style="display: none;">Install App</button>

            </div>
        </div>

        <script>
            $(document).ready(function () {

                $('#login_form').validate({
                    rules: {
                        password: {
                            required: true,
                        },
                        login: {
                            required: true,
                        }
                    }
                });
            });
        </script>
    </body>
</html>