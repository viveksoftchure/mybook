<?php
$from = ifset($_POST,'from', date('Y-m-01'));
$to = ifset($_POST,'to', date('Y-m-t'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h4>Add Weight</h4>
            </div>
            <div class="ibox-content">
                <form role="form" action="" method="post" id="add_form">
                    <input type="hidden" name="add_weight" value="1">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <input type="text" name="weight" class="form-control" placeholder="66.66"/>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary" type="submit"><i class="svg-icon svg-search"></i>&nbsp;&nbsp;Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h4>Options</h4>
            </div>
            <div class="ibox-content">
                <form role="form" action="" method="post" id="search_form">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <input class="form-control datepicker" type="date" placeholder="From" name="from" value="<?= isset($_REQUEST['from'])?$_REQUEST['from']:$from ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                 <input class="form-control datepicker" placeholder="To" type="date" name="to" value="<?= isset($_REQUEST['to'])?$_REQUEST['to']:$to ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary" type="submit" name="simple_search"><i class="svg-icon svg-search"></i>&nbsp;&nbsp;Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>Weight</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div id="weight-chart"></div>
            </div>
        </div>
    </div>
</div>

<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
<script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
<script src="js/plugins/morris/morris.js"></script>

<script type="text/javascript">
//Flot Multiple Axes Line Chart
$(function() {
    var weightData = <?= json_encode($data['weight_data']) ?>;

    Morris.Line({
        element: 'weight-chart',
        data: weightData,
        xkey: 'year',
        ykeys: ['value'],
        // ymin: 'auto',
        ymax : 80,
        ymin: 50,
        labels: ['Value'],
        lineColors: ['#1ab394'],
        pointSize:5,
        yLabelFormat: function(y) {
            // Format y-axis labels to two decimal places
            return parseFloat(y).toFixed(2);
        }
    });
});
</script>