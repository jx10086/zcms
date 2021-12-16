<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p><a href="javascript:dr_update_cache_all();"><?php echo dr_lang('更改数据之后请更新下全站缓存'); ?></a></p>
</div>
<?php echo \Phpcmf\Service::L('Field')->get('select')->get_select_search_code(); ?>
<div class="right-card-box">
    <form class="form-horizontal" role="form" id="myform">
        <?php echo dr_form_hidden(); ?>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable dataTable">
                <thead>
                <tr class="heading">
                    <?php if ($ci->_is_admin_auth('del')) { ?>
                    <th class="myselect">
                        <label class="mt-table mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="group-checkable" data-set=".checkboxes" />
                            <span></span>
                        </label>
                    </th>
                    <?php } ?>
                    <th class="hidden-mobile" width="70" style="text-align:center"> <?php echo dr_lang('排序'); ?> </th>
                    <th class="hidden-mobile" width="60" style="text-align:center"> <?php echo dr_lang('可用'); ?> </th>
                    <th width="300"> <?php echo dr_lang('名称'); ?> </th>
                    <?php if (dr_count($ci->site_info) > 1) { ?>
                    <th width="100">
                        <?php if ($ci->_is_admin_auth('add')) { ?><a href="javascript:dr_iframe('add', '<?php echo dr_url('menu/add', ['pid'=>0]); ?>', '600px', '500px');" class="btn btn-xs blue"> <i class="fa fa-plus"></i> <?php echo dr_lang('添加'); ?> </a><?php } ?>
                    </th>
                    <th> <?php echo dr_lang('站点划分'); ?> </th>
                    <?php } else { ?>
                    <th>
                        <?php if ($ci->_is_admin_auth('add')) { ?><a href="javascript:dr_iframe('add', '<?php echo dr_url('menu/add', ['pid'=>0]); ?>');" class="btn btn-xs blue"> <i class="fa fa-plus"></i> <?php echo dr_lang('添加'); ?> </a><?php } ?>
                    </th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php $top=[];  if (is_array($data)) { $key_t=-1;$count_t=dr_count($data);foreach ($data as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; $t['pid'] == 0 && $top[] = $t['id'];$t['site'] = dr_string2array($t['site']); ?>
                <tr class="odd gradeX" id="dr_row_<?php echo $t['id']; ?>">
                    <?php if ($ci->_is_admin_auth('del')) { ?>
                    <td class="myselect">
                        <label class="mt-table mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="checkboxes checkboxes<?php echo $t['tid']; ?> checkboxes<?php echo $t['pid']; ?> group-checkable" data-set=".checkboxes<?php echo $t['id']; ?>"  name="ids[]" value="<?php echo $t['id']; ?>" />
                            <span></span>
                        </label>
                    </td>
                    <?php } ?>
                    <td class="hidden-mobile" style="text-align:center"> <input type="text" onblur="dr_ajax_save(this.value, '<?php echo dr_url('menu/save_edit', ['id'=>$t['id']]); ?>', 'displayorder')" value="<?php echo $t['displayorder']; ?>" class="displayorder form-control input-sm input-inline input-mini"> </td>
                    <td class="hidden-mobile" style="text-align:center">
                        <a href="javascript:;" onclick="dr_ajax_open_close(this, '<?php echo dr_url('menu/use_edit', ['id'=>$t['id']]); ?>', 1);" class="badge badge-<?php if ($t['hidden']) { ?>no<?php } else { ?>yes<?php } ?>"><i class="fa fa-<?php if ($t['hidden']) { ?>times<?php } else { ?>check<?php } ?>"></i></a>
                    </td>
                    <td>
                        <?php echo $t['spacer']; ?> <a href="javascript:dr_iframe('edit', '<?php echo dr_url('menu/edit', ['id'=>$t['id']]); ?>', '600px');"><i class="<?php echo $t['icon']; ?>"></i> <?php echo dr_lang($t['name']); ?></a>
                    </td>
                    <td>
                        <?php if ($ci->_is_admin_auth('add') && ($t['pid'] == 0 || dr_in_array($t['pid'], (array)$top))) { ?><a href="javascript:dr_iframe('add', '<?php echo dr_url('menu/add', ['pid'=>$t['id']]); ?>', '600px', '<?php if (!in_array($t['pid'], $top)) { ?>500px<?php } ?>');" class="btn btn-xs blue"> <i class="fa fa-plus"></i> <?php echo dr_lang('添加'); ?> </a><?php } ?>
                    </td>
                    <?php if (dr_count($ci->site_info) > 1) { ?>
                    <td>
                        <label><a title="<?php echo $ci->site_info[1]['SITE_NAME']; ?>" class="btn btn-xs dark" style="background-color:#3fa9e4;border-color:#3fa9e4"> <?php echo dr_lang('主站'); ?> </a></label>
                        <?php if (is_array($t['site'])) { $key_sid=-1;$count_sid=dr_count($t['site']);foreach ($t['site'] as $b=>$sid) { $key_sid++; $is_first=$key_sid==0 ? 1 : 0;$is_last=$count_sid==$key_sid+1 ? 1 : 0;  if ($sid > 1) { ?>
                        <label id="dr_site_<?php echo $t['id']; ?>_<?php echo $sid; ?>"><a href="javascript:dr_site_delete('<?php echo $t['id']; ?>', '<?php echo $sid; ?>');" title="<?php echo $ci->site_info[$sid]['SITE_NAME']; ?>" class="btn btn-xs <?php if ($color[$b]) {  echo $color[$b];  } else { ?>default<?php } ?>"><?php echo dr_strcut($ci->site_info[$sid]['SITE_NAME'], 4); ?> <i class="fa fa-times"></i> </a></label>
                        <?php }  } } ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php } } ?>
                </tbody>
            </table>
        </div>
        <div class="row fc-list-footer table-checkable ">
            <div class="col-md-12 fc-list-select">
                <?php if ($ci->_is_admin_auth('del')) { ?>
                <label class="mt-table mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="group-checkable" data-set=".checkboxes" />
                    <span></span>
                </label>
                <button type="button" onclick="dr_ajax_option('<?php echo dr_url('menu/del'); ?>', '<?php echo dr_lang('你确定要删除它们吗？'); ?>', 1)" class="btn red btn-sm"> <i class="fa fa-trash"></i> <?php echo dr_lang('删除'); ?></button>
                <?php }  if ($ci->_is_admin_auth('edit') && dr_count($ci->site_info) > 1) { ?>
                <label style="margin-left: 20px;"><button type="button" onclick="dr_ajax_option('<?php echo dr_url('menu/site_add'); ?>', '<?php echo dr_lang('你确定要这样操作吗？'); ?>', 1)" class="btn blue btn-sm"> <i class="fa fa-edit"></i> <?php echo dr_lang('批量划分站点权限'); ?></button></label>
                <label style="min-width: 200px;">
                    <select name="siteid[]" class="bs-select form-control"  multiple="multiple"  data-actions-box="true">
                        <?php if (is_array($ci->site_info)) { $key_t=-1;$count_t=dr_count($ci->site_info);foreach ($ci->site_info as $sid=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;  if ($sid > 1) { ?>
                        <option value="<?php echo $t['SITE_ID']; ?>"><?php echo $t['SITE_NAME']; ?></option>
                        <?php }  } } ?>
                    </select>
                </label>
                <?php } ?>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    function dr_site_delete(id, sid) {
        <?php if (!$ci->_is_admin_auth('del')) { ?>
        dr_tips(0, '<?php echo dr_lang('无权限删除'); ?>');
        return;
        <?php } ?>
            var index = layer.load(2, {
                shade: [0.3,'#fff'], //0.1透明度的白色背景
                time: 10000
            });
            $.ajax({
                type: "GET",
                cache: false,
                url: '<?php echo dr_url('menu/site_del'); ?>&id='+id+'&sid='+sid,
            dataType: "json",
            success: function (json) {
            layer.close(index);
            if (json.code == 1) {
                $('#dr_site_'+id+'_'+sid).hide();
                dr_tips(1, json.msg);
            } else {
                dr_tips(0, json.msg);
            }
        },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_alert_error(HttpRequest, this, thrownError);;
            }
        });
        }
</script>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>