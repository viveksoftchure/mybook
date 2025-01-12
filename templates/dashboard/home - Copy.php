<?php //dump($data['results']) ?>

<div class="row">
    <div class="col-lg-12">

        <div class="grid grid-cols-4 grid-gap-15 mb-4">
            <div class="grid-item expanses-item">
                <h5 class="expanses-title">Last 7 Day's Expense</h5>
                <p class="expanses-amount"><?= $data['this_week_expense'] ? formatToINR($data['this_week_expense']) : 0; ?></p>
            </div>

            <div class="grid-item expanses-item">
                <h5 class="expanses-title">Last 30 Day's Expense</h5>
                <p class="expanses-amount"><?= $data['this_month_expense'] ? formatToINR($data['this_month_expense']) : 0; ?></p>
            </div>

            <div class="grid-item expanses-item">
                <h5 class="expanses-title">Current Year Expense</h5>
                <p class="expanses-amount"><?= $data['this_year_expense'] ? formatToINR($data['this_year_expense']) : 0; ?></p>
            </div>

            <div class="grid-item expanses-item">
                <h5 class="expanses-title">Total Expense</h5>
                <p class="expanses-amount"><?= $data['total_expense'] ? formatToINR($data['total_expense']) : 0; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row p-sm m-t">
    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-title"><h5>Filters</h5></div>
            <div class="ibox-content">
                <form role="form" action="" method="post" id="search_form">
                    <input type="hidden" name="page" value="1">
                    <div class="form-group">
                        <input type="text" name="keywords" placeholder="Title, Description..." class="form-control" value="<?=ifset($_POST,'keywords')?>">
                    </div>

                    <div class="form-group">
                        <select name="id_category" id="id_category" class="form-control">
                            <option class="placeholder" value="">Select category</option>
                            <?php foreach($this->category->getPaymentCategories() as $key => $category): ?>
                                <option value="<?=$category['id_category']?>" <?=($category['id_category'] == ifset($_REQUEST,'id_category'))?'selected="selected"':''?>><?=$category['title']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Select Payment method</option>
                            <?php foreach($this->category->getPaymentMethods() as $key => $category): ?>
                                <option value="<?=$category['id_category']?>" <?=($category['id_category'] == ifset($_REQUEST,'payment_method'))?'selected="selected"':''?>><?=$category['title']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-primary" type="submit" name="simple_search"><i class="svg-icon svg-search"></i>&nbsp;&nbsp;Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-content">

                <?php
                $page = isset($_POST['page']) ? $_POST['page'] : 1;
                $pageCount = isset($data['pageCount']) ? $data['pageCount'] : 0;

                list($min, $max) = getPageRange($page, $pageCount);
                ?>

                <?php if($pageCount) { ?>
                    <div class="pagination-container mb-custom-2">
                        <div class="btn-group">
                            <a href="#" data-page="1" type="button" class="btn btn-primary pagination-btn"><i class="svg-icon svg-arrow-left"></i></a>
                            <?php foreach (range($min, $max) as $number): ?>
                                <a href="#" data-page="<?= $number ?>" class="btn btn-primary pagination-btn <?= $page==$number?'active':'' ?>"><?= $number ?></a>
                            <?php endforeach; ?>
                            <a href="#" data-page="<?= $pageCount ?>" type="button" class="btn btn-primary pagination-btn"><i class="svg-icon svg-arrow-right"></i> </a>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if(count($data['results'])): ?>
                    <ul class="payment-list">
                        <?php foreach($data['results'] as $history_key => $items): ?>
                            <li class="history-title"><?= date('F Y', strtotime($history_key)) ?></li>
                            <?php foreach($items as $key => $item): ?>
                                <li class="history-item">
                                    <div class="item-category" style="background: <?=$item['category_data']['icon_bg']?>">
                                        <i class="<?=ifset($item['category_data'], 'icon', 'Blank')?>" style="background: <?=ifset($item['category_data'], 'icon_color', 'Blank')?>"></i>
                                    </div>
                                    <div class="item-content">
                                        <h2 class="item-title"><?= ifset($item['category_data'], 'title', '') ?></h2>
                                        <span class="item-date"><?= date('d M, g:i A', strtotime($item['date'])) ?></span>
                                    </div>
                                    <div class="item-last">
                                        <h2 class="item-ammount"><?= formatToINR($item['amount']) ?></h2>
                                        <span class="item-method">Paid from <?=ifset($item['payment_method_data'], 'title', 'Blank')?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if($pageCount) { ?>
                    <div class="pagination-container mb-custom-2">
                        <div class="btn-group">
                            <a href="#" data-page="1" type="button" class="btn btn-primary pagination-btn"><i class="svg-icon svg-arrow-left"></i></a>
                            <?php foreach (range($min, $max) as $number): ?>
                                <a href="#" data-page="<?= $number ?>" class="btn btn-primary pagination-btn <?= $page==$number?'active':'' ?>"><?= $number ?></a>
                            <?php endforeach; ?>
                            <a href="#" data-page="<?= $pageCount ?>" type="button" class="btn btn-primary pagination-btn"><i class="svg-icon svg-arrow-right"></i> </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script>
$(document).ready(function() 
{
    /*
    ** hide event block
    */
    $('.close-event').on('click', function(e)
    {
        e.preventDefault();
        var $this = $(this);
        var box = $this.data('box');
        
        $.ajax({
            url: '<?= $data['updateUrl']; ?>',
            data: {
                hide_calendar: '1',
            },
            type: "POST",
            success: function (response) 
            {
                var el = $.parseJSON(response);

                $('.'+box).addClass('hiden-calendar-notification');
            }
        });
    });

    /*
    ** show calendar on click
    */
    $(document).on('click', '.hiden-calendar-notification', function(e)
    {
        e.preventDefault();
        var $this = $(this);

        $this.removeClass('hiden-calendar-notification');
        $.ajax({
            url: '<?= $data['updateUrl']; ?>',
            data: {
                show_calendar: '1',
            },
            type: "POST",
            success: function (response) 
            {
                var el = $.parseJSON(response);
            }
        });
    });

    // on click pagination change page
    $('.pagination-btn').on('click', function(e)
    {
        e.preventDefault();
        var $page = $(this).data('page');
        $('#search_form').find('input[name="page"]').val($page);

        $('#search_form').submit();
    });

});
</script>