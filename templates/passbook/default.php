<?php

$keywords = isset($_POST['filter']['keywords']) ? $_POST['filter']['keywords'] : '';
$id_category = isset($_POST['filter']['id_category']) ? $_POST['filter']['id_category'] : '';
$date_start = isset($_POST['filter']['date_start']) ? $_POST['filter']['date_start'] : '';
$date_end = isset($_POST['filter']['date_end']) ? $_POST['filter']['date_end'] : '';
$paid_for = isset($_POST['filter']['paid_for']) ? $_POST['filter']['paid_for'] : '';
$payment_method = isset($_POST['filter']['payment_method']) ? $_POST['filter']['payment_method'] : '';
// echo '<pre>'; print_r($data['history']); echo '</pre>'; 
// exit;
?>

<div class="row p-sm m-t">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title"><h5>Filters</h5></div>
            <div class="ibox-content">
                <form role="form" action="" method="post" id="search_form">
                    <input type="hidden" name="page" value="1">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <input type="date" name="filter[date_start]" id="date_start" class="form-control" value="<?=$date_start?>">
                        </div>

                        <div class="form-group col-md-2">
                            <input type="date" name="filter[date_end]" id="date_end" class="form-control" value="<?=$date_end?>">
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" name="filter[keywords]" placeholder="Title, Description..." class="form-control" value="<?=$keywords?>">
                        </div>

                        <div class="form-group col-md-2">
                            <select name="filter[id_category]" id="id_category" class="form-control">
                                <option class="placeholder" value="">Select category</option>
                                <?php foreach($this->category->getPaymentCategories() as $key => $category): ?>
                                    <option value="<?=$category['id_category']?>" <?=($category['id_category'] == $id_category)?'selected="selected"':''?>><?=$category['title']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <select name="filter[paid_for]" id="paid_for" class="form-control">
                                <option value="">Select User</option>
                                <?php foreach ($this->user->getUsersOptions() as $key => $item): ?>
                                    <option value="<?= $item['id_user'] ?>" <?=($item['id_user'] == $paid_for)?'selected="selected"':''?>><?= $item['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <button class="btn btn-primary" type="submit" name="simple_search"><i class="svg-icon svg-search"></i>&nbsp;&nbsp;Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($data['history']): ?>
            <?php foreach($data['history'] as $year => $year_item): ?>
                <div class="ibox">
                    <div class="ibox-title">
                        <h4><?=$year?> </h4>
                        <div class="ibox-tools">
                            <button type="button" class="btn btn-primary new-payment" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-table="#payments_table_<?=$year?>" data-type="cr"><i class="svg-icon svg-budget"></i>&nbsp;&nbsp;Add New income</button>
                            <button type="button" class="btn btn-danger new-payment" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-table="#payments_table_<?=$year?>" data-type="dr"><i class="svg-icon svg-budget"></i>&nbsp;&nbsp;Add New outcome</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="payments_table_<?=$year?>" class="table table-hover footable vertical-aligned" data-page="false">
                                <thead>
                                    <tr>
                                        <th width="100">Date</th>
                                        <!-- <th style="width: 15%">Name</th> -->
                                        <?php if($this->isAdmin()): ?>
	                                        <th width="100">Status</th>
	                                    <?php endif; ?>
                                        <th width="100">Category</th>
                                        <th width="100">Payment Method</th>
                                        <th width="100">Payment Type</th>
                                        <th width="100">Payment For</th>
                                        <th width="150">Paid From / To</th>
                                        <!-- <th style="width: 20%">Description</th> -->
                                        <th width="100">Debit</th>
                                        <th width="100">Credit</th>
                                        <th width="150"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_debit = 0;
                                $total_credit = 0;
                                ?>
                                <?php foreach($year_item as $item): ?>
                                    <?php
                                    $payment_type = $item['payment_type_data'];
                                    $status_data = $item['status_data'];

                                    $total_debit = $item['payment_type'] == 'dr' ? $total_debit + $item['amount'] : $total_debit;
                                    $total_credit = $item['payment_type'] == 'cr' ? $total_credit + $item['amount'] : $total_credit;
                                    ?>
                                    <tr id="payment_<?=$item['id_payment']?>">
                                        <td>
                                            <?= date('d M Y', strtotime($item['date'])) ?>
                                            <?php if($this->isAdmin()): ?>
                                                <br>
                                                <?= date('h:i A', strtotime($item['date'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <!-- <td><?=$item['title']?></td> -->
                                        <?php if($this->isAdmin()): ?>
	                                        <td>
	                                            <?php if($item['status']!=''): ?>
	                                                <span class="badge badge-<?=$status_data['color']?>">
	                                                    <?=ifset($status_data, 'title', '')?>
	                                                </span>
	                                            <?php endif; ?>
	                                        </td>
	                                    <?php endif; ?>
                                        <td><?=ifset($item['category_data'], 'title', '')?></td>
                                        <td><?=ifset($item['payment_method_data'], 'title', '')?></td>
                                        <td>
                                            <span class="badge badge-<?=$payment_type['color']?>"><?=$payment_type['title']?></span>
                                        </td>
                                        <td><?=$item['paid_for_name']?></td>
                                        <td><?=$item['paid_to']?></td>
                                        <!-- <td><?=$item['description']?></td> -->
                                        <td style=""><?= $item['payment_type'] == 'dr' ? formatToINR($item['amount']) : '' ?></td>
                                        <td style=""><?= $item['payment_type'] == 'cr' ? formatToINR($item['amount']) : '' ?></td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-payment" role="button" data-id="<?=$item['id_payment']?>" data-table="#payments_table_<?=$year?>"><i class="svg-icon svg-delete"></i><span class="text-danger"></span></button>
                                            <button type="button" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-payment-id="<?=$item['id_payment']?>" data-table="#payments_table_<?=$year?>"><i class="svg-icon svg-setting"></i></button>
                                            <button type="button" class="btn btn-default" role="button" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-payment-id="<?=$item['id_payment']?>" data-payment-duplicate="1" data-table="#payments_table_<?=$year?>"><i class="svg-icon svg-clone"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <th><span class="badge badge-danger"><?= formatToINR($total_debit) ?></span></th>
                                    <th><span class="badge badge-primary"><?= formatToINR($total_credit) ?></span></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="ibox">
                <div class="ibox-title">
                    <h4><?=date('Y')?> </h4>
                    <div class="ibox-tools">
                        <button type="button" class="btn btn-primary new-payment" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-table="#payments_table_<?=$year?>" data-type="cr"><i class="svg-icon svg-budget"></i>&nbsp;&nbsp;Add New income</button>
                        <button type="button" class="btn btn-danger new-payment" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-table="#payments_table_<?=date('Y')?>" data-type="dr"><i class="svg-icon svg-budget"></i>&nbsp;&nbsp;Add New outcome</button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="payments_table_<?=date('Y')?>" class="table table-hover footable vertical-aligned" data-page="false">
                            <thead>
                                <tr>
                                    <th width="100">Date</th>
                                    <!-- <th style="width: 15%">Name</th> -->
                                    <th width="100">Status</th>
                                    <th width="100">Category</th>
                                    <th width="100">Payment Method</th>
                                    <th width="100">Payment Type</th>
                                    <th width="100">Payment For</th>
                                    <th width="150">Paid From / To</th>
                                    <!-- <th style="width: 20%">Description</th> -->
                                    <th width="100">Debit</th>
                                    <th width="100">Credit</th>
                                    <th width="150"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th><span class="badge badge-danger"></span></th>
                                <th><span class="badge badge-primary"></span></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- start payment modal -->
<div id="update_payment_form" class="hidden">
    <div class="hidden">
        <div class="form-title">Add new payment</div><div class="form-subtitle"></div>
        <div class="form-img"><h1><i class="svg-icon svg-budget"></i></h1></div>
        <input type="hidden" id="add_payment" name="add_payment" value="1" disabled="disabled">
        <input type="hidden" id="update_payment" name="update_payment" value="1" disabled="disabled">
        <input type="hidden" name="form_type" value="">
        <input type="hidden" name="id_payment" id="id_payment" value="">
    </div>
    <?php
    $hide = $this->isAdmin() ? '' : 'hidden';
    ?>
    <div class="form-group row m-b-none <?= $hide ?>">
        <label class="col-lg-6 col-form-label"><strong>Status</strong></label>
        <div class="col-lg-6 p-xxs">
            <select name="status" id="status" class="form-control">
                <option value="">Select status</option>
                <?php
                foreach ($this->passbook->getPaymentStatusOptions() as $key => $status) {
                    echo '<option value="'.$key.'" '.($key==='completed'?'selected':'').'>'.$status['title'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed m-xs <?= $hide ?>"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Category</strong></label>
        <div class="col-lg-6 p-xxs">
            <select name="id_category" id="id_category" class="form-control">
                <option value="">Select category</option>
                <?php
                foreach ($this->category->getPaymentCategories() as $key => $category) {
                    echo '<option value="'.$category['id_category'].'">'.$category['title'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Payment method</strong></label>
        <div class="col-lg-6 p-xxs">
            <select name="payment_method" id="payment_method" class="form-control">
                <option value="">Select category</option>
                <?php
                foreach ($this->category->getPaymentMethods() as $key => $category) {
                    echo '<option value="'.$category['id_category'].'">'.$category['title'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <div class="form-group row m-b-none <?= $hide ?>">
        <label class="col-lg-6 col-form-label"><strong>Payment type</strong></label>
        <div class="col-lg-6 p-xxs">
            <select name="payment_type" id="payment_type" class="form-control">
                <option value="dr">Debit</option>
                <option value="cr">Credit</option>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed m-xs <?= $hide ?>"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Paid for</strong></label>
        <div class="col-lg-6 p-xxs">
            <select name="paid_for" id="paid_for" class="form-control" required="required">
                <option value="">Select User</option>
                <?php foreach ($this->user->getUsersOptions() as $key => $item): ?>
                    <option value="<?= $item['id_user'] ?>"><?= $item['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Date</strong></label>
        <div class="col-lg-6 p-xxs">
            <div class="input-group date_block"  style="position:relative">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="date" name="date" id="date" class="form-control date" autocomplete="false" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <div class="form-group row m-b-none <?= $hide ?>">
        <label class="col-lg-6 col-form-label"><strong>Time</strong></label>
        <div class="col-lg-6 p-xxs">
            <div class="input-group mb-3">
                <span class="input-group-addon"><i class="fa fa-clock"></i></span>
                <input type="time" name="time" id="time" class="form-control time" value="<?= date('H:i') ?>">
            </div>
        </div>
    </div>
    <div class="hr-line-dashed m-xs <?= $hide ?>"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Amount</strong></label>
        <div class="col-lg-6 p-xxs">
            <input type="number" name="amount" id="amount" value="" class="form-control" required="required">
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Paid From / To</strong></label>
        <div class="col-lg-6 p-xxs">
            <input type="text" name="paid_to" id="paid_to" value="" class="form-control" required="required">
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div>

    <!-- <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Title</strong></label>
        <div class="col-lg-6 p-xxs">
            <input type="text" name="title" id="title" value="" class="form-control" required="required">
        </div>
    </div>
    <div class="hr-line-dashed m-xs"></div> -->

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>Description</strong></label>
        <div class="col-lg-6 p-xxs">
            <textarea name="description" id="description" class="form-control" row="2"></textarea>
        </div>
    </div>
    
    <div id="item_form_container">

    </div>
    <hr class="hr-line-solid">

    <div class="form-group row m-b-none">
        <div class="col-lg-12 p-xxs text-right">
            <button class="btn btn-default btn-sm" type="button" id="add_item"><i class="svg-icon svg-plus-large"></i> Add Item</span></button>
        </div>
    </div>
</div>
<!-- end payment modal -->

<!-- Add Table Row -->
<div id="item_form" class="hidden">
    <div id="item_box_%ID%" class="item-box">
        <hr class="hr-line-solid">
        <div class="form-group row m-b-none">
            <label class="col-lg-2 col-form-label"><strong>Item %ID%</strong></label>
            <div class="col-lg-5 p-xxs">
                <input type="text" name="item[%ID%][item_name]" placeholder="Item Name" class="form-control" value="%NAME%">
            </div>
            <div class="col-lg-2 p-xxs">
                <input type="text" name="item[%ID%][item_weight]" placeholder="Item Weight" class="form-control" value="%WEIGHT%">
            </div>
            <div class="col-lg-2 p-xxs">
                <input type="number" name="item[%ID%][item_price]" placeholder="Item Price" class="form-control" min="0" value="%PRICE%">
            </div>
            <div class="col-lg-1 p-xxs">
                <button class="btn btn-danger remove_address" type="button" data-in-use="0"><i class="svg-icon svg-delete"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- end Address form -->

<!-- Address form -->
<table id="table_row" class="hidden">
    <tbody>
        <tr id="payment_%ID%">
            <td>%DATE%</td>
            <!-- <td>%TITLE%</td> -->
            <?php if($this->isAdmin()): ?>
                <td><span class="badge badge-%ST_COLOR%">%STATUS%</span></td>
            <?php endif; ?>
            <td>%CATEGORY%</td>
            <td>%PAYMENT_METHOD%</td>
            <td><span class="badge badge-%PT_COLOR%">%PAYMENT_TYPE%</span></td>
            <td>%PAID_FOR%</td>
            <td>%PAID_TO%</td>
            <!-- <td>%DESCRIPTION%</td> -->
            <td style="">%DEBIT%</td>
            <td style="">%CREDIT%</td>
            <td>
                <button type="button" class="btn btn-danger remove-payment" role="button" data-id="%ID%" data-table="#payments_table_%YEAR%"><i class="svg-icon svg-delete"></i><span class="text-danger"></span></button>
                <button type="button" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-payment-id="%ID%" data-table="#payments_table_%YEAR%"><i class="svg-icon svg-setting"></i></button>
                <button type="button" class="btn btn-default" role="button" data-toggle="modal" data-target="#modal" data-form="#update_payment_form" data-payment-id="%ID%" data-payment-duplicate="1" data-table="#payments_table_%YEAR%"><i class="svg-icon svg-clone"></i></button>
            </td>
        </tr>
    </tbody>
</table>
<!-- end Address form -->

<script>
$(document).ready(function() {

    $('#modal').on('show.bs.modal', function (event) {
        let modal = $(this);
        const button = $(event.relatedTarget);
        const buttonSave = modal.find('#save_form');
        const form = modal.find('form');

        let id = button.data('payment-id');
        var table = button.data('table');
        var type = button.data('type');
        var ns_id = 0;
        
        let duplicate = button.data('payment-duplicate');

        modal.find('#payment_type').val(type);

        if (id) {
            if (duplicate) {
                modal.find('.modal-title').text('Duplicate payment');
                modal.find('#add_payment').attr('disabled', false);
                modal.find('input[name=form_type]').val('add');
            } else {
                modal.find('.modal-title').text('Update payment');
                modal.find('input[name=id_payment]').val(id);
                modal.find('#update_payment').attr('disabled', false);
                modal.find('input[name=form_type]').val('update');
            }
            modal.find('.dp1').hide();

            $.get('<?=$data['updateUrl']?>', {
                get_payment: "1", 
                id_payment: id
            }).done(function (data) {
                var el = $.parseJSON(data);
                // modal.find('input[name=title]').val(el.title);
                modal.find('select[name=status]').val(el.status);
                modal.find('select[name=id_category]').val(el.id_category);
                modal.find('select[name=payment_method]').val(el.payment_method);
                modal.find('select[name=payment_type]').val(el.payment_type);
                modal.find('input[name=date]').val(el.date);
                modal.find('input[name=time]').val(el.time);
                modal.find('input[name=paid_to]').val(el.paid_to);
                modal.find('select[name=paid_for]').val(el.paid_for);
                modal.find('input[name=amount]').val(el.amount);
                modal.find('textarea[name=description]').val(el.description);

                if (el.items) {
                    $.each(el.items, function(index, item) {
                        var template = $('#item_form').html();
                        template = template.replaceAll('%ID%', index);
                        template = template.replaceAll('%NAME%', item.item_name);
                        template = template.replaceAll('%WEIGHT%', item.item_weight);
                        template = template.replaceAll('%PRICE%', item.item_price);

                        modal.find('#item_form_container').append(template);
                        ns_id = index;
                    });
                }
            });
        } else {
            modal.find('#add_payment').attr('disabled', false);
            modal.find('input[name=form_type]').val('add');
        }

        $("#block_form").validate({
            ignore: ":hidden",
            rules: {
                title: {
                    required: true,
                },
            },
            submitHandler: function (form) {
                var form = modal.find('form');
                var id = modal.find('input[name=id_payment]').val();
                var formType = modal.find('input[name=form_type]').val();

                var status          = modal.find('#status').val();
                var id_category     = modal.find('#id_category').val();
                var payment_method  = modal.find('#payment_method').val();
                var payment_type    = modal.find('#payment_type').val();
                var paid_for_name   = modal.find('#paid_for_name').val();
                var date            = modal.find('#date').val();
                var time            = modal.find('#time').val();
                var amount          = modal.find('#amount').val();
                var title           = modal.find('#title').val();
                // var description     = modal.find('#description').val();

                $.ajax({
                    type: 'POST',
                    url: '<?=$data['updateUrl']?>',
                    data: form.serialize(),
                    success: function (response) {
                        let el = $.parseJSON(response);

                        if (formType=='update') {
                            var parent = $(table).find('#payment_'+el.id);

                            let debit = el.debit>0 ? formatToINR(el.debit) : '';
                            let credit = el.credit>0 ? formatToINR(el.credit) : '';

                            var template = $('#table_row').find('tbody tr').html();
                            template = template.replaceAll('%ID%', el.id);
                            template = template.replaceAll('%DATE%', el.date);
                            template = template.replaceAll('%YEAR%', el.year);
                            // template = template.replaceAll('%TITLE%', el.title);
                            template = template.replaceAll('%STATUS%', el.status_data.title);
                            template = template.replaceAll('%ST_COLOR%', el.status_data.color);
                            template = template.replaceAll('%CATEGORY%', el.category_data.title);
                            template = template.replaceAll('%PAYMENT_METHOD%', el.payment_method_data.title);
                            template = template.replaceAll('%PT_COLOR%', el.payment_type_data.color);
                            template = template.replaceAll('%PAYMENT_TYPE%', el.payment_type_data.title);
                            template = template.replaceAll('%PAID_FOR%', el.paid_for_name);
                            template = template.replaceAll('%PAID_TO%', el.paid_to);
                            // template = template.replaceAll('%DESCRIPTION%', el.description);
                            template = template.replaceAll('%DEBIT%', debit);
                            template = template.replaceAll('%CREDIT%', credit);

                            parent.html(template);

                        } else {
                            let debit = el.debit>0 ? formatToINR(el.debit) : '';
                            let credit = el.credit>0 ? formatToINR(el.credit) : '';

                            var template = $('#table_row').find('tbody').html();
                            template = template.replaceAll('%ID%', el.id);
                            template = template.replaceAll('%DATE%', el.date);
                            template = template.replaceAll('%YEAR%', el.year);
                            // template = template.replaceAll('%TITLE%', el.title);
                            template = template.replaceAll('%STATUS%', el.status_data.title);
                            template = template.replaceAll('%ST_COLOR%', el.status_data.color);
                            template = template.replaceAll('%CATEGORY%', el.category_data.title);
                            template = template.replaceAll('%PAYMENT_METHOD%', el.payment_method_data.title);
                            template = template.replaceAll('%PT_COLOR%', el.payment_type_data.color);
                            template = template.replaceAll('%PAYMENT_TYPE%', el.payment_type_data.title);
                            template = template.replaceAll('%PAID_FOR%', el.paid_for_name);
                            template = template.replaceAll('%PAID_TO%', el.paid_to);
                            // template = template.replaceAll('%DESCRIPTION%', el.description);
                            template = template.replaceAll('%DEBIT%', debit);
                            template = template.replaceAll('%CREDIT%', credit);

                            $(table).find('tbody').prepend(template);
                        }

                        $('#modal').modal('hide');
                    }
                });
                return false; // required to block normal submit since you used ajax
            }
        });

        modal.find('#add_item').off().on('click', function() {
            ns_id++;
            var container = modal.find('#item_form_container');
            var template = $('#item_form').html();
            template = template.replaceAll('%ID%', ns_id);
            template = template.replaceAll('%NAME%', '');
            template = template.replaceAll('%WEIGHT%', '');
            template = template.replaceAll('%PRICE%', '');

            container.append(template);
        });

        modal.on('click', 'button.remove_address', function() {
            if (confirm("Do you really want to remove this address?")) {
                $(this).closest('.item-box').remove();
            }
        });
    });

    /*
     * on hide modal unbind the events
     */
    $('#modal').on('hide.bs.modal', function (event) {
        let modal = $(this);
        modal.off('click', 'button.remove_address');
        modal.off('click', '#save_form');
        ns_id = 1;
    });
    
    $(document).find('.remove-payment').off().on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');

        if (confirm('Are you sure you want to remove this payment?')) {
            $.ajax({
                type: 'POST',
                url: '<?=$data['updateUrl']?>',
                data: {
                    remove_payment: 1,
                    id_payment: id,
                },
                success: function (response) {
                    let el = $.parseJSON(response);

                    $('#payment_'+id).remove();
                }
            });
        } else {
            return false;
        }
    });
});
</script>