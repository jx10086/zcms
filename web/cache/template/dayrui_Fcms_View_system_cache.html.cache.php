<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p><a href="javascript:dr_update_cache('', '');"><?php echo dr_lang('更改数据之后需要更新缓存之后才能生效'); ?></a></p>
</div>

<form action="" class="form-horizontal" method="post" name="myform" id="myform">
    <?php echo $form; ?>
    <div class="myfbody">
    <div class="portlet bordered light ">
        <div class="portlet-title tabbable-line">
            <ul class="nav nav-tabs" style="float:left;">
                <li class="<?php if ($page==0) { ?>active<?php } ?>">
                    <a href="#tab_0" data-toggle="tab" onclick="$('#dr_page').val('0')"> <i class="fa fa-cog"></i> <?php echo dr_lang('缓存设置'); ?> </a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content">

                <div class="tab-pane <?php if ($page==0) { ?>active<?php } ?>" id="tab_0">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('缓存开关'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_CACHE]" value="1" <?php if ($data['SYS_CACHE']) { ?>checked<?php } ?> /> <?php echo dr_lang('开启'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_CACHE]" value="0" <?php if (empty($data['SYS_CACHE'])) { ?>checked<?php } ?> /> <?php echo dr_lang('关闭'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('推荐开启缓存功能，可以大大提高系统运行效率'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('缓存方式'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_CACHE_TYPE]" value="0" <?php if (empty($data['SYS_CACHE_TYPE'])) { ?>checked<?php } ?> /> <?php echo dr_lang('文件'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_CACHE_TYPE]" value="1" <?php if ($data['SYS_CACHE_TYPE'] == 1) { ?>checked<?php } ?> /> Memcached <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_CACHE_TYPE]" value="2" <?php if ($data['SYS_CACHE_TYPE'] == 2) { ?>checked<?php } ?> /> Redis <span></span></label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="data[SYS_CACHE_SMS]" value="<?php echo intval($data['SYS_CACHE_SMS']); ?>">

                        <?php if (is_array($cache_var)) { $key_name=-1;$count_name=dr_count($cache_var);foreach ($cache_var as $value=>$name) { $key_name++; $is_first=$key_name==0 ? 1 : 0;$is_last=$count_name==$key_name+1 ? 1 : 0; ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang($name); ?></label>
                            <div class="col-md-9">
                                <div class="input-inline input-medium">
                                    <div class="input-group">
                                        <input type="text" name="data[SYS_CACHE_<?php echo $value; ?>]" value="<?php echo floatval($data['SYS_CACHE_'.$value]); ?>" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="help-inline"> <?php echo dr_lang('单位小时，0表示不缓存'); ?> </span>
                            </div>
                        </div>
                        <?php } } ?>


                    </div>
                </div>



            </div>
        </div>
    </div>
    </div>
    <div class="portlet-body form myfooter">
        <div class="form-actions text-center">
            <label><button type="button" onclick="dr_ajax_submit('<?php echo dr_now_url(); ?>&page='+$('#dr_page').val(), 'myform', '2000')" class="btn blue"> <i class="fa fa-save"></i> <?php echo dr_lang('保存'); ?></button></label>
            <label><button type="button" onclick="dr_test_cache()" class="btn red"> <i class="fa fa-cloud"></i> <?php echo dr_lang('测试'); ?></button></label>
        </div>
    </div>
</form>

<script type="text/javascript">
    function dr_test_cache() {
        var loading = layer.load(2, {
            shade: [0.3,'#fff'], //0.1透明度的白色背景
            time: 10000
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo dr_url('api/test_cache'); ?>",
			data: $('#myform').serialize(),
            success: function(json) {
                layer.close(loading);
                dr_tips(json.code, json.msg);
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_alert_error(HttpRequest, this, thrownError);
            }
        });
    }
</script>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>