<?php
// echo '<pre>'; print_r($_SESSION); echo '</pre>'; 
// exit;
?>

<div class="row p-sm m-t">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>Action</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <a class="btn btn-default generate-export" href="<?= get_url('advanced', 'export', 'generate_new=1&file=passbook') ?>"><i class="svg-icon svg-csv-file-format" aria-hidden="true"></i>&nbsp;&nbsp;Generate new files CSV/XLXS</a>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4><?=count($data['history'])?> </h4>
            </div>
            <?php if(count($data['history'])): ?>
                <div class="ibox-content">
                    <div class="table-responsive">
                    <table id="export_table" class="table table-hover footable vertical-aligned" data-page="false">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach($data['history'] as $item): ?>
                                <tr id="export_<?=$item['id_export']?>">
                                    <td><?=$item['id_export']?></td>
                                    <td><?=date('d M Y h:i', strtotime($item['created_at']))?></td>
                                    <td><?=$item['file']?></td>
                                    <td>
                                        <a href="<?=$item['link_csv']?>" class="btn btn-default" download>Download Export</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

});
</script>