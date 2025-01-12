<?php $v='2.31'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" /> 
    
    <title><?=$this->getTitle();?></title>
    <link rel="icon" type="image/png" href="img/favicon.png">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v=<?=$v?>" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/co2.css" rel="stylesheet">

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/plugins/validate/jquery.validate.min.js"></script>

    <script src="<?=$this->getBaseUrl()?>js/fontawesome-6.js" crossorigin="anonymous"></script>
    <base href="<?=$this->getBaseUrl()?>">

    <style type="text/css">
        label.error {
             margin-left: 0px; 
        }
    </style>
</head>

<body class="gray-bg">
    <div class="middle-box loginscreen animated fadeInDown text-center">
        <form method="post" action="" id="reset_form" class="connect-form">
            <div class="pb-4">
                <img src="img/logo.png" width="120" />
            </div>

            <h3 class="m-b-md"><?=$this->__('Welcome to audit application Qualiopi')?> Certif<span>opac</span></h3>
            <?php if ($data['validationStatus'] == 'invalid_code'): ?>
                <div class="text-validation">
                    <b><?=$this->__('This code is not valid')?></b>
                </div>
            <?php elseif($data['validationStatus'] == 'success'): ?>
                <div class="font-weight-middle">
                    <?=$this->__('Your password has been updated.')?><br><?=$this->__('Click here to login')?>
                    <a href="index.php" class="btn btn-primary full-width m-b m-t"><?=$this->__('Log in')?></a>
                </div>
            <?php else: ?>
                <div class="font-weight-middle">
                    <?=$this->__('Hi')?> <b><?=ifset($data['user'],'first_name')?></b>,<br><br>
                    <?=$this->__('Please enter your new password for your account')?><br><b><?=ifset($data['user'],'email')?></b>
                </div>
                <br>
                <div class="form-group">
                    <div class="">
                        <input type="password" name="password" id="password" class="form-control" placeholder="<?=$this->__('Password')?>" required="required">
                        <i class="toggle-password fa-regular fa-eye-slash"></i>
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <input type="password" name="password_confirm" class="form-control" placeholder="<?=$this->__('Confirm password')?>" required="required">
                        <i class="toggle-password fa-regular fa-eye-slash"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary full-width m-b m-t"><?=$this->__('Reset')?></button>
            <?php endif;?>

            <?php if ($data['validationStatus'] == 'fail'): ?>
                <div class="text-validation m-b">
                    <b><?=$this->__('Something went wrong. Try again.')?></b><br />
                </div>
            <?php endif;?>

        </form>
        <div class="connect-discover m-t-md font-weight-middle"><?=$this->__('Discover the application')?></div>
    </div>
<script>
$(document).ready(function () {

    $.validator.addMethod("strong_password", function (value, element) {
        let password = value;
        if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(.{8,20}$)/.test(password))) {
            return false;
        }
        return true;
    }, function (value, element) {
        let password = $(element).val();
        let rightPwd = false;
        let message  = '<?=$this->__('Password must contain at least 8 characters and must contain at least one lowercase, one uppercase and one numeric character.')?>';

        if (!(/^(.{8,20}$)/.test(password))) {
            // return 'Password must be between 8 to 20 characters long.';
            rightPwd = true;
        }
        else if (!(/^(?=.*[A-Z])/.test(password))) {
            // return 'Password must contain at least one uppercase.';
            rightPwd = true;
        }
        else if (!(/^(?=.*[a-z])/.test(password))) {
            // return 'Password must contain at least one lowercase.';
            rightPwd = true;
        }
        else if (!(/^(?=.*[0-9])/.test(password))) {
            // return 'Password must contain at least one digit.';
            rightPwd = true;
        }

        return rightPwd ? message : false;
    });

    $('#reset_form').validate({
        rules: {
            password: {
                required: true,
                strong_password: true,
            },
            password_confirm: {
                required: true,
                equalTo: "#password",
            }
        },
        messages: {
            password_confirm: {
                required: '<?=$this->__('Confirmation of your password is not correct', true)?>',
                equalTo: '<?=$this->__('Confirmation of your password is not correct', true)?>',
            },
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('.form-group'));
            // error.insertAfter(element);
        },
    });

    /*
    * toggle password type
    */
    $(document).on('click', '.toggle-password', function() {
        $(this).toggleClass("svg-eye-open svg-eye-close");
        input = $(this).parent().find("input");
        if (input.hasClass('password-visible')) {
            input.removeClass('password-visible');
        } else {
            input.addClass('password-visible');
        }
    });
});
</script>
</body>
</html>