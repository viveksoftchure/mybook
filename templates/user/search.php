<?php
// echo '<pre>'; print_r($data['users']); echo '</pre>'; 
// exit;
?>
<div class="row p-sm bg-muted">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h4><?=count($data['users'])?> user</h4>
            </div>
            <?php if(count($data['users'])): ?>
                <div class="ibox-content table-responsive">
                    <table class="table footable vertical-aligned" data-page="false">
                        <thead>
                        <tr>
                            <th data-sort-ignore="true">#</th>
                            <th data-sort-ignore="true">Name</th>
                            <th data-sort-ignore="true">Category</th>
                            <th data-sort-ignore="true">E-mail address</th>
                            <th data-sort-ignore="true">Status</th>
                            <th data-sort-ignore="true"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data['users'] as $item): ?>
                            <tr>
                                <td><?= $item['id_user'] ?></td>
                                <td class="d-flex align-items-center">
                                    <img src="<?=$item['profile']?>" class="img-md mr-2 fit-img" alt="profile"> 
                                    <?=$item['first_name']?> <?=$item['last_name']?>
                                </td>
                                <td><?= $item['id_category'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td>
                                    <?php if($item['status']): $meta = $this->user->getUserStatusData($item['status'])?>
                                        <span class="badge badge-<?=$meta['color']?>"><?=$meta['title']?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?=$item['remove_link']?>" class="btn btn-default" role="button" onclick="return confirm('Are you sure you want to remove this user?');"><i class="svg-icon svg-delete"></i> <span class="text-danger">Remove</span></a>
                                    <a href="<?=$item['send_link']?>" class="btn btn-default" role="button" onclick="return confirm('Are you sure you want to send email invitation to this user?);"><i class="svg-icon svg-email"></i> Send access invitation</a>
                                    <a href="#" class="btn btn-default" role="button" data-toggle="modal" data-target="#modal" data-form="#update_user_form" data-user-id="<?=$item['id_user']?>"><i class="svg-icon svg-user-pen"></i> Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row gray-bg p-sm">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Options</h5>
                <div class="ibox-tools"></div>
            </div>
            <div class="ibox-content">
                <a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal" data-form="#update_user_form"><i class="svg-icon svg-user-plus"></i>&nbsp;&nbsp;Add new user</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal forms -->
<!-- Update user form -->
<div id="update_user_form" class="hidden">
    <div class="hidden">
        <div class="form-title">Add new user</div><div class="form-subtitle"></div>
        <div class="form-img"><h1><i class="svg-icon svg-user-plus"></i></h1></div>
        <input type="hidden" id="add_user" name="add_user" value="1" disabled="disabled">
        <input type="hidden" id="update_user" name="update_user" value="1" disabled="disabled">
        <input type="hidden" name="id_user" value="">
    </div>
    
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Picture</strong></label>
        <div class="col-lg-8 p-xxs">
            <div class="custom-file">
                <input id="file" name="picture" type="file" class="custom-file-input">
                <label for="file" class="custom-file-label">Choose file...</label>
            </div>
        </div>
    </div>

    <div class="hr-line-dashed m-xs dp1"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>First name</strong></label>
        <div class="col-lg-8 p-xxs">
            <input type="text" name="first_name" placeholder="First name" required="required" class="form-control m-t-xs">
        </div>
    </div>

    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Last name</strong></label>
        <div class="col-lg-8 p-xxs">
            <input type="text" name="last_name" placeholder="Last name" required="required" class="form-control m-t-xs">
        </div>
    </div>

    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Email</strong></label>
        <div class="col-lg-8 p-xxs">
            <input type="text" name="email" placeholder="Email" class="form-control m-t-xs">
        </div>
    </div>

    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-9 col-form-label"><strong>Status</strong></label>
        <div class="col-lg-3 p-xxs">
            <div class="row">
                <div class="col i-checks"><label> <input type="radio" value="enabled" name="status" required="required"> <i></i> Enabled </label></div>
                <div class="col i-checks"><label> <input type="radio" value="disabled" name="status"> <i></i> Disabled </label></div>
            </div>
        </div>
    </div>

    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-9 col-form-label"><strong>Category</strong></label>
        <div class="col-lg-3 p-xxs">
            <div class="row">
                <div class="col i-checks"><label> <input type="radio" value="1" name="id_category" required="required"> <i></i> Admin </label></div>
                <div class="col i-checks"><label> <input type="radio" value="2" name="id_category"> <i></i> User </label></div>
            </div>
        </div>
    </div>

    <div class="hr-line-dashed m-xs dp1"></div>
    <div class="form-group row m-b-none dp1">
        <label class="col-lg-11 col-form-label"><strong>Send email invitation</strong></label>
        <div class="col-lg-1 p-xxs">
            <div class="row text-right">
                <div class="col i-checks"><input type="checkbox" value="1" name="send_invitation"></div>
            </div>
        </div>
    </div>
</div>
<!-- end Update user form -->
<!-- end Modal forms -->

<script>
$(document).ready(function() {
    $('#modal').on('show.bs.modal', function (event) {
        let modal = $(this);
        let id = $(event.relatedTarget).data('user-id');

        if (id) {
            modal.find('.modal-title').text('Update user');
            modal.find('input[name=id_user]').val(id);
            modal.find('#update_user').attr('disabled', false);
            modal.find('.dp1').hide();

            $.get('<?=$data['updateUrl']?>', {
                get_user: "1", 
                id_user: id
            }).done(function (data) {
                var el = $.parseJSON(data);
                modal.find('input[name=first_name]').val(el.first_name);
                modal.find('input[name=last_name]').val(el.last_name);
                modal.find('input[name=email]').val(el.email);
                modal.find('#block_form input[name=status][value="' + el.status + '"]').prop("checked", true);
                modal.find('#block_form input[name=id_category][value="' + el.id_category + '"]').prop("checked", true);
            });
        } else {
            modal.find('#add_user').attr('disabled', false);
            modal.find('#block_form input[name=status][value="enabled"]').prop("checked", true);
        }

        jQuery.validator.addMethod("checkEmail", function(value) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(value);
        });

        modal.find('#block_form').validate({
            rules: {
                // email: {
                //     required: true,
                //     checkEmail: true,
                //     remote: function() {
                //         return '<?=$data['checkEmailUrl']?>' + '&id_user=' + modal.find('input[name=id_user]').val()
                //     }
                // }
            },
            messages: {
                'email': {
                    checkEmail: "This is a incorrect format",
                    remote: 'This email is already used'
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr('name') == 'status') {
                    error.appendTo(element.closest('.row'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });

    $('#modal').on('hide.bs.modal', function () 
    {
        $('input[name=status]').prop("checked", false);
        $('input[name=id_user]').val(0);
        $('.dp1').show();
    });
});
</script>