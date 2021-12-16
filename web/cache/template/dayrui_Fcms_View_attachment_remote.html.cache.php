<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<div class="note note-danger">
    <p><a href="javascript:dr_update_cache('urlrule', '');"><?php echo dr_lang('更改数据之后需要更新缓存之后才能生效'); ?></a></p>
</div>

<div class="right-card-box">
<form class="form-horizontal" role="form" id="myform">
    <?php echo dr_form_hidden(); ?>
    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-hover table-checkable dataTable">
            <thead>
            <tr class="heading">
                <?php if ($ci->_is_admin_auth('del')) { ?>
                <th class="myselect">
                    <label class="mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                        <input type="checkbox" class="group-checkable" data-set=".checkboxes" />
                        <span></span>
                    </label>
                </th>
                <?php } ?>
                <th width="50" class="<?php echo dr_sorting('id'); ?>" name="id">Id</th>
                <th style="text-align:center" class="<?php echo dr_sorting('type'); ?>" name="type" width="90"><?php echo dr_lang('存储类型'); ?></th>
                <th class="<?php echo dr_sorting('name'); ?>" name="name"><?php echo dr_lang('名称'); ?></th>
                <th><?php echo dr_lang('操作'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (is_array($list)) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
            <tr class="odd gradeX" id="dr_row_<?php echo $t['id']; ?>">
                <?php if ($ci->_is_admin_auth('del')) { ?>
                <td class="myselect">
                    <label class="mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                        <input type="checkbox" class="checkboxes" name="ids[]" value="<?php echo $t['id']; ?>" />
                        <span></span>
                    </label>
                </td>
                <?php } ?>
                <td><?php echo $t['id']; ?></td>
                <td style="text-align:center"> <span class="badge badge-<?php echo $color[$t['type']]; ?>"> <?php echo $ci->type[$t['type']]['name']; ?> </span></td>
                <td><?php echo $t['name']; ?></td>
                <td>
                    <?php if ($ci->_is_admin_auth('edit')) { ?>
                    <label><a href="<?php echo dr_url('attachment/edit', ['id'=>$t['id']]); ?>" class="btn btn-xs green"> <i class="fa fa-edit"></i> <?php echo dr_lang('修改'); ?></a></label>
                    <?php } ?>
                </td>
            </tr>
            <?php } } ?>
            </tbody>
        </table>
    </div>

         <div class="row fc-list-footer table-checkable ">
             <div class="col-md-5 fc-list-select">
                <?php if ($ci->_is_admin_auth('del')) { ?>
                <label class="mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="group-checkable" data-set=".checkboxes" />
                    <span></span>
                </label>
                <button type="button" onclick="dr_ajax_option('<?php echo dr_url('attachment/del'); ?>', '<?php echo dr_lang('删除后，已关联的附件都会失效，确定要删除吗？'); ?>', 1)" class="btn red btn-sm"> <i class="fa fa-trash"></i> <?php echo dr_lang('删除'); ?></button>
                <?php } ?>
            </div>
             <div class="col-md-7 fc-list-page">
                 <?php echo $mypages; ?>
             </div>
         </div>
</form>

</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>