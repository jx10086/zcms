<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p>升级程序之前，请务必备份全站数据</p>
</div>

<div class="right-card-box">
<form class="form-horizontal" role="form" id="myform">
    <?php echo dr_form_hidden(); ?>
    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-hover table-checkable dataTable">
            <thead>
            <tr class="heading">
                <th width="80"> 类型</th>
                <th width="250"> 程序名称</th>
                <th width="110"> 更新时间 </th>
                <th width="100"> 版本 </th>
                <th width="110" style="text-align: center"> 备份 </th>
                <th> </th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;  if (is_array($list)) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $dir=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
            <tr class="odd gradeX">
                <td><?php echo $t['tname']; ?></td>
                <td><?php echo $t['name'];  if ($t['type'] == 'app') { ?> / <?php echo $dir;  } ?></td>
                <td> <?php echo $t['updatetime']; ?> </td>
                <td><?php if (!$is_oem) { ?><a href="javascript:dr_show_log('<?php echo $t['id']; ?>', '<?php echo $t['version']; ?>');"><?php echo $t['version']; ?></a><?php } else {  echo $t['version'];  } ?></td>
                <td align="center">
					<?php if ($t['backup']) { ?>
                    <a href="javascript:dr_tips(1, '备份目录：<?php echo $t['backup']; ?>', -1);" class="label label-success"> 已备份 </a>
                    <?php } else { ?>
                    <span class="label label-danger"> 未备份 </span>
                    <?php } ?>
                </td>
                <td>
                    <label style="display: none" id="dr_update_<?php echo $dir; ?>">
					<button type="button" onclick="dr_update_cms('<?php echo dr_url('cloud/todo_update', ['id'=>$t['id'], 'dir'=>$dir]); ?>', '<?php echo dr_lang('升级前请做好系统备份，你确定要升级吗？'); ?>', 1)" class="btn red btn-xs"> <i class="fa fa-cloud-upload"></i> <?php echo dr_lang('在线升级'); ?></button>
					<?php if (!$is_oem) {  if ($dir == 'phpcmf') { ?>
					    <a href="https://www.xunruicms.com/member.php?action=down&cid=<?php echo $cmf_version['id']; ?>&is_update=v<?php echo $t['version']; ?>" target="_blank" class="btn green btn-xs"> <i class="fa fa-cloud-download"></i> <?php echo dr_lang('离线下载'); ?></a>
					    <?php } else { ?>
                        <a href="https://www.xunruicms.com/doc/641.html" target="_blank" class="btn green btn-xs"> <i class="fa fa-cloud-download"></i> <?php echo dr_lang('离线下载'); ?></a>
					    <?php }  } ?>
					</label>
                    <label style="display: none" id="dr_duibi_<?php echo $dir; ?>">
					<a href="<?php echo dr_url('cloud/bf'); ?>" class="btn red btn-xs"> <i class="fa fa-code"></i> <?php echo dr_lang('文件对比升级'); ?></a>
					<?php if (IS_DEV) { ?>
					<button type="button" onclick="dr_update_cms('<?php echo dr_url('cloud/todo_update', ['id'=>$t['id'], 'dir'=>$dir]); ?>', '<?php echo dr_lang('更新前请做好系统备份，你确定要更新吗？'); ?>', 1)" class="btn red btn-xs"> <i class="fa fa-cloud-upload"></i> <?php echo dr_lang('在线更新'); ?></button>
					<?php } ?>
                    <label class="dr_check_version" id="dr_row_<?php echo $dir; ?>"></label>
                </td>
            </tr>
            <?php $i++;  } } ?>
            </tbody>
        </table>
    </div>
    <?php if (!$is_oem) { ?>
    <div class="row fc-list-footer table-checkable ">
        <div class="col-md-12">
            <label><a href="https://www.xunruicms.com/doc/video-%E8%BF%85%E7%9D%BFCMS%E5%9C%A8%E7%BA%BF%E5%8D%87%E7%BA%A7%E6%95%99%E7%A8%8B.html" target="_blank" class="btn green btn-sm"> <i class="fa fa-book"></i> <?php echo dr_lang('在线升级教程'); ?></a></label>
            <label><a href="https://www.xunruicms.com/doc/video-%E8%BF%85%E7%9D%BFCMS%E7%A6%BB%E7%BA%BF%E5%8D%87%E7%BA%A7%E6%95%99%E7%A8%8B.html" target="_blank" class="btn blue btn-sm"> <i class="fa fa-book"></i> <?php echo dr_lang('离线升级教程'); ?></a></label>
        </div>
    </div>
    <?php } ?>
</form>
</div>
<script type="text/javascript">

    $(function() {
        <?php if (is_array($list)) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $dir=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;  if ($t['id']) { ?>
        $("#dr_row_<?php echo $dir; ?>").html("<img style='height:17px' src='<?php echo THEME_PATH; ?>assets/images/loading-0.gif'>");
        $("#dr_update_<?php echo $dir; ?>").hide();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "<?php echo dr_url('cloud/check_version', ['id'=>$t['id'], 'version' => $t['version']]); ?>",
            success: function(json) {
                if (json.code == 1) {
                    $("#dr_row_<?php echo $dir; ?>").html(json.msg);
                    $("#dr_update_<?php echo $dir; ?>").show();
                } else if (json.code == 2) {
                    $("#dr_row_<?php echo $dir; ?>").html(json.msg);
                    $("#dr_duibi_<?php echo $dir; ?>").show();
                } else {
                    $("#dr_row_<?php echo $dir; ?>").html("<font color='red'>"+json.msg+"</font>");
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                $("#dr_row_<?php echo $dir; ?>").html("<font color='red'>网络异常，请稍后再试</font>");
            }
        });
        <?php }  } } ?>
    });

    // ajax 批量操作确认
    function dr_update_cms(url, msg, remove) {
        layer.confirm(
        msg,
        {
            icon: 3,
            shade: 0,
            title: lang['ts'],
            btn: ['直接升级','备份再升级', lang['esc']]
        }, function(index, layero){
            layer.close(index);
            dr_todo_cms(url+'&is_bf=1');
        }, function(index){
            layer.close(index);
            dr_todo_cms(url+'&is_bf=0');
        });
    }

    function dr_todo_cms(url) {
        var login_url = '<?php echo dr_url("cloud/login"); ?>';
        layer.open({
            type: 2,
            title: '登录官方云账号',
            fix:true,
            scrollbar: false,
            shadeClose: true,
            shade: 0,
            area: ['500px', '260px'],
            btn: [lang['ok'], lang['esc']],
            yes: function(index, layero){
                var body = layer.getChildFrame('body', index);
                $(body).find('.form-group').removeClass('has-error');
                // 延迟加载
                var loading = layer.load(2, {
                    shade: [0.3,'#fff'], //0.1透明度的白色背景
                    time: 100000000
                });
                $.ajax({type: "POST",dataType:"json", url: login_url, data: $(body).find('#myform').serialize(),
                    success: function(json) {
                        layer.close(loading);
                        if (json.code == 1) {
                            layer.close(index);
                            var yz_url = url+'&'+$('#myform').serialize()+'&ls='+json.data;
                            // 验证成功
                            layer.open({
                                type: 2,
                                title: '升级程序',
                                scrollbar: false,
                                resize: true,
                                maxmin: true, //开启最大化最小化按钮
                                shade: 0,
                                area: ['80%', '80%'],
                                success: function(layero, index){
                                    // 主要用于后台权限验证
                                    var body = layer.getChildFrame('body', index);
                                    var json = $(body).html();
                                    if (json.indexOf('"code":0') > 0 && json.length < 150){
                                        var obj = JSON.parse(json);
                                        layer.closeAll(index);
                                        dr_tips(0, obj.msg);
                                    }
                                },
                                content: yz_url
                            });
                        } else {
                            $(body).find('#dr_row_'+json.data.field).addClass('has-error');
                            dr_tips(0, json.msg);
                        }
                        return false;
                    },
                    error: function(HttpRequest, ajaxOptions, thrownError) {
                        dr_ajax_alert_error(HttpRequest, this, thrownError);
                    }
                });
                return false;
            },
            content: login_url+'&is_ajax=1'
        });
    }

    function dr_beifen_cms(url, msg, remove) {
        layer.confirm(
                msg,
                {
                    icon: 3,
                    shade: 0,
                    title: lang['ts'],
                    btn: [lang['ok'], lang['esc']]
                }, function(index){
                    layer.close(index);
                    layer.open({
                        type: 2,
                        title: '备份程序',
                        scrollbar: false,
                        resize: true,
                        maxmin: true, //开启最大化最小化按钮
                        shade: 0,
                        area: ['80%', '80%'],
                        success: function(layero, index){
                            // 主要用于后台权限验证
                            var body = layer.getChildFrame('body', index);
                            var json = $(body).html();
                            if (json.indexOf('"code":0') > 0 && json.length < 150){
                                var obj = JSON.parse(json);
                                layer.closeAll(index);
                                dr_tips(0, obj.msg);
                            }
                        },
                        content: url
                    });
                });
    }
    
    function dr_show_log(id, v) {
        layer.open({
            type: 2,
            title: '版本日志',
            scrollbar: false,
            resize: true,
            maxmin: true, //开启最大化最小化按钮
            shade: 0,
            area: ['80%', '80%'],
            content: '<?php echo dr_url("cloud/log_show"); ?>&id='+id+'&version='+v,
            //content: 'http://www.phpcmf.net/version.php?id='+id+'&version='+v,
        });
    }

</script>


<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>