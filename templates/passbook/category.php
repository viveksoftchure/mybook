<div class="row p-sm m-t">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h4>Create new category</h4>
            </div>
            <div class="ibox-content">
                <a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal" data-form="#update_category_form"><i class="svg-icon svg-plus"></i>&nbsp;&nbsp;Create new category</a>
            </div>
        </div>
        
        <?php foreach($this->categoryTypes() as $key => $type): ?>
            <?php $categories = $this->category->getAllCategories($key) ?>
            <div class="ibox">
                <div class="ibox-title">
                    <h4><?=count($categories)?> <?= $type ?></h4>
                </div>
                <?php if(count($categories)): ?>
                    <div class="ibox-content">
                        <div class="table-responsive">
                        <table class="table footable vertical-aligned" data-page="false">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Icon</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($categories as $item): ?>
                                <tr>
                                    <td><?=$item['id_category']?></td>
                                    <td>
                                        <span class="badge badge-<?=$item['status']=='enabled'?'primary':'warning'?>"><?=$item['status']?></span>
                                    </td>
                                    <td><?=$item['title']?></td>
                                    <td><div class="icon-box" style="background: <?=$item['icon_bg']?>"><i class="<?=$item['icon']?>" style="background: <?=$item['icon_color']?>"></i></div></td>
                                    <td><?=$item['description']?></td>
                                    <td>
                                        <a href="<?=$item['remove_link']?>" class="btn btn-default" role="button" onclick="return confirm('Are you sure you want to remove this category?');"><i class="svg-icon svg-delete"></i>&nbsp;&nbsp;<span class="text-danger">Remove</span></a>
                                        <a href="#" class="btn btn-default" role="button" data-toggle="modal" data-target="#modal" data-form="#update_category_form" data-category-id="<?=$item['id_category']?>"><i class="svg-icon svg-edit"></i>&nbsp;&nbsp;Update category</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal forms -->
<!-- Update category form -->
<div id="update_category_form" class="hidden">
    <div class="hidden">
        <div class="form-title">Add new category</div><div class="form-subtitle"></div>
        <div class="form-img"><h1><i class="svg-icon svg-edit"></i></h1></div>
        <input type="hidden" id="add_category" name="add_category" value="1" disabled="disabled">
        <input type="hidden" id="update_category" name="update_category" value="1" disabled="disabled">
        <input type="hidden" name="id_category" value="">
    </div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Title</strong></label>
        <div class="col-lg-8 p-xxs">
            <input type="text" name="title" placeholder="Title" required="required" class="form-control m-t-xs">
        </div>
    </div>

    <div class="hr-line-dashed m-xs dp1"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Type</strong></label>
        <div class="col-lg-8 p-xxs">
            <select class="form-control" name="type">
                <?php foreach($this->categoryTypes() as $key => $item): ?>
                    <option value="<?= $key ?>"><?= $item ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="hr-line-dashed m-xs dp1"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Icon</strong></label>
        <div class="col-lg-8 p-xxs">
            <input type="text" name="icon" placeholder="Icon" required="required" class="form-control m-t-xs">
        </div>
    </div>

    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Icon Color</strong></label>
        <div class="col-lg-8 p-xxs">
            <div class="input-group">
                <div class="input-group-prepend">
                    <input type="color" class="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                </div>
                <input type="text" name="icon_color" placeholder="#397bbe" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" class="form-control hexcolor">
            </div>
        </div>
    </div>
    
    <div class="hr-line-dashed m-xs"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Icon BgColor</strong></label>
        <div class="col-lg-8 p-xxs">
            <div class="input-group">
                <div class="input-group-prepend">
                    <input type="color" class="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                </div>
                <input type="text" name="icon_bg" placeholder="#397bbe" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" class="form-control hexcolor">
            </div>
        </div>
    </div>

    <div class="hr-line-dashed m-xs dp1"></div>
    <div class="form-group row m-b-none">
        <label class="col-lg-4 col-form-label"><strong>Description</strong></label>
        <div class="col-lg-8 p-xxs">
            <textarea name="description" id="description" class="form-control m-t-xs"></textarea>
        </div>
    </div>
</div>
<!-- end Update category form -->
<!-- end Modal forms -->

<script>
$(document).ready(function() {
    $('#modal').on('show.bs.modal', function (event) {
        let modal = $(this);
        let id = $(event.relatedTarget).data('category-id');
        const buttonSave = modal.find('.btn-primary');
        const form = modal.find('form');

        if (id) {
            modal.find('.modal-title').text('Update category');
            modal.find('input[name=id_category]').val(id);
            modal.find('#update_category').attr('disabled', false);
            modal.find('.dp1').hide();

            $.get('<?=$data['updateUrl']?>', {get_category: "1", id_category: id})
                .done(function (data) {
                    var el = $.parseJSON(data);
                    modal.find('input[name=title]').val(el.title);
                    modal.find('select[name=type]').val(el.type);
                    modal.find('input[name=icon]').val(el.icon);
                    modal.find('input[name=description]').val(el.description);

                    modal.find('input[name=icon_color]').val(el.icon_color);
                    modal.find('input[name=icon_color]').parent().find('.colorpicker').val(el.icon_color);

                    modal.find('input[name=icon_bg]').val(el.icon_bg);
                    modal.find('input[name=icon_bg]').parent().find('.colorpicker').val(el.icon_bg);
                });
        } else {
            modal.find('#add_category').attr('disabled', false);
        }

        modal.find('.colorpicker').on('input', function() {
            $(this).parent().parent().find('.hexcolor').val(this.value);
        });
        modal.find('.hexcolor').on('input', function() {
          $(this).parent().find('.colorpicker').val(this.value);
        });
    });

    $('#modal').on('hide.bs.modal', function () {
        $('input[name=id_category]').val(0);
        $('.dp1').show();
    });
});
</script>