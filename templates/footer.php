	    	</div>
	    </main>

	    <div class="footer no-borders gray-bg">
	        <div class="text-center">
	            <small>Copyright MyBook &copy; <?= date('Y') ?> - Provided by WpWebGuru</small>
	        </div>
	    </div>

	    <!-- Modal window -->
	    <div class="modal inmodal fade" id="modal" role="dialog" aria-hidden="true" tabindex="-1">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                    <div class="modal-img"><h1><i class="fa fa-edit"></i></h1></div>
	                    <h4 class="modal-title"></h4>
	                    <small class="modal-subtitle"></small>
	                </div>
	                <div class="modal-body">
	                    <form action="" method="post" role="form" id="block_form" enctype="multipart/form-data"></form>
	                </div>
	                <div class="modal-footer text-right">
	                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
	                    <button type="button" class="btn btn-primary" id="save_form">Save</button>
	                </div>
	            </div>
	        </div>
	    </div>

	    <!-- update password form -->
	    <div id="update_password_form" class="hidden">
	        <div class="hidden">
	            <div class="form-title">Update my password</div>
	            <div class="form-img"><h1><i class="svg-icon svg-key"></i></h1></div>
	            <input type="hidden" name="update_password" value="1">
	        </div>
	        
	        <div class="form-group row m-b-none">
	            <label class="col-lg-4 col-form-label"><strong>Current password</strong></label>
	            <div class="col-lg-8 p-xxs">
	                <div>
	                    <input type="text" name="old_password" id="old_password" placeholder="Current password" required="required" class="form-control m-t-xs password-field">
	                    <i class="toggle-password svg-icon svg-eye-close"></i>
	                </div>
	            </div>
	        </div>

	        <div class="hr-line-dashed m-xs"></div>
	        <div class="form-group row m-b-none">
	            <label class="col-lg-4 col-form-label"><strong>New password</strong></label>
	            <div class="col-lg-8 p-xxs">
	                <div>
	                    <input type="text" name="password" id="password" placeholder="New password" required="required" class="form-control m-t-xs password-field">
	                    <i class="toggle-password svg-icon svg-eye-close"></i>
	                </div>
	            </div>
	        </div>

	        <div class="hr-line-dashed m-xs"></div>
	        <div class="form-group row m-b-none">
	            <label class="col-lg-4 col-form-label"><strong>Confirm new password</strong></label>
	            <div class="col-lg-8 p-xxs">
	                <div>
	                    <input type="text" name="password_confirm" id="password_confirm" placeholder="Confirm new password" required="required" class="form-control m-t-xs password-field">
	                    <i class="toggle-password svg-icon svg-eye-close"></i>
	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- end update password form -->

	    <!-- end Modal window -->

	    <script>
	    const removeConfirm = "Are you sure you want to remove this item?";
	    $(document).ready(function() {
	        $('.change-menu').click(function(event){
	            let button = $(this);
	            let view = button.data('view');
	            let dataObj = {};
	            dataObj.update_menu = 1;
	            dataObj.view = (view === 1) ? 0 : 1 ;
	            $.post('<?=$this->getCurrentUrl()?>', dataObj)
	                .done(function(response) {
	                    if (response == 'true') {
	                        button.data('view', dataObj.view);
	                        if (dataObj.view) {
	                            $('body').addClass('mini-navbar');
	                            button.find('div').html("Big menu");
	                        } else {
	                            $('body').removeClass('mini-navbar');
	                            button.find('div').html("Small menu");
	                        }
	                    }
	                });
	            event.preventDefault();
	        });

	        $('.change-mode').on('click', function(event) {
	            let button = $(this);
	            let mode = button.data('mode');
	            let dataObj = {};
	            dataObj.update_mode = 1;
	            dataObj.mode = (mode === 1) ? 0 : 1;

	            $.post('<?=$this->getCurrentUrl()?>', dataObj)
	                .done(function(response) {
	                    if (response == 'true') {
	                        button.data('mode', dataObj.mode);
	                        if (dataObj.mode) {
	                            button.find('div').html("Light mode");
	                            $('body').addClass('dark-mode');
	                        } else {
	                            button.find('div').html("Dark mode");
	                            $('body').removeClass('dark-mode');
	                        }
	                    }
	                });
	            event.preventDefault();
	        });

	        $('#modal').on('show.bs.modal', function (event) {
	            const button = $(event.relatedTarget);
	            const modal = $(this);

	            var newBlock = '<div class="hr-line-dashed m-xs"></div><div class="forgot-password"><a href="index.php?t1=login&t2=forgot" target="_blank">Forgot your password?</a></div>';

	            if (button.data('form') == '#update_password_form') {
	                modal.find('.modal-footer').append(newBlock);
	                modal.find('input.form-control').val('');

	                $.validator.addMethod("strong_password", function (value, element) {
	                    let password = value;
	                    if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(.{8,20}$)/.test(password))) {
	                        return false;
	                    }
	                    return true;
	                }, function (value, element) {
	                    let password = $(element).val();
	                    let rightPwd = false;
	                    let message  = 'Password must contain at least 8 characters and must contain at least one lowercase, one uppercase and one numeric character.';

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

	                $('#block_form').validate({
	                    rules: {
	                        old_password: {
	                            required: true,
	                            remote: function() {
	                                return '<?=$this->getUrl($this->t1, $this->t2, 'check_old_password=1')?>'
	                            }
	                        },
	                        password: {
	                            required: true,
	                            strong_password: true,
	                        },
	                        password_confirm: {
	                            equalTo: "#password",
	                        }
	                    },
	                    messages: {
	                        'old_password': {
	                            remote: 'The current password is incorrect'
	                        },
	                        'password_confirm': {
	                            required: 'Confirmation of your password is not correct',
	                            equalTo: 'Confirmation of your password is not correct'
	                        }
	                    },
	                    errorPlacement: function(error, element) {
	                        error.appendTo(element.closest('.col-lg-8'));
	                        // error.insertAfter(element);
	                    },
	                    submitHandler: function(form) {
	                        form.submit();
	                    }
	                });
	            }
	        });

	        $('#modal').on('hide.bs.modal', function (event) {
	            $(this).find('.modal-footer .hr-line-dashed').remove();
	            $(this).find('.modal-footer .forgot-password').remove();
	        });
	    });
		</script>
	</body>
</html>