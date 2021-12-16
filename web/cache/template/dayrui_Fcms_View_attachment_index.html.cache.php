<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<div class="note note-danger">
    <p><a href="javascript:dr_update_cache();"><?php echo dr_lang('更改数据之后需要更新缓存之后才能生效'); ?></a></p>
</div>
<form action="" class="form-horizontal" method="post" name="myform" id="myform">
    <?php echo $form; ?>
    <div class="myfbody">
    <div class="portlet bordered light ">
        <div class="portlet-title tabbable-line">
            <ul class="nav nav-tabs" style="float:left;">
                <li class="<?php if ($page==0) { ?>active<?php } ?>">
                    <a href="#tab_0" data-toggle="tab" onclick="$('#dr_page').val('0')"> <i class="fa fa-cog"></i> <?php echo dr_lang('附件设置'); ?> </a>
                </li>
                <li class="<?php if ($page==2) { ?>active<?php } ?>">
                    <a href="#tab_2" data-toggle="tab" onclick="$('#dr_page').val('2')"> <i class="fa fa-user"></i> <?php echo dr_lang('头像存储'); ?> </a>
                </li>
                <li class="<?php if ($page==1) { ?>active<?php } ?>">
                    <a href="#tab_1" data-toggle="tab" onclick="$('#dr_page').val('1')"> <i class="fa fa-photo"></i> <?php echo dr_lang('[%s]缩略图', SITE_NAME); ?> </a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content">



                <div class="tab-pane <?php if ($page==0) { ?>active<?php } ?>" id="tab_0">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('附件归档'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_DB]" value="1" <?php if ($data['SYS_ATTACHMENT_DB']) { ?>checked<?php } ?> /> <?php echo dr_lang('开启'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_DB]" value="0" <?php if (empty($data['SYS_ATTACHMENT_DB'])) { ?>checked<?php } ?> /> <?php echo dr_lang('关闭'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('附件将分为已使用的附件和未使用的附件，归档存储'); ?></span>
                            </div>
                        </div>

                        <?php if (!IS_USE_MEMBER) { ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('游客上传'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_GUEST]" value="1" <?php if ($data['SYS_ATTACHMENT_GUEST']) { ?>checked<?php } ?> /> <?php echo dr_lang('开启'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_GUEST]" value="0" <?php if (empty($data['SYS_ATTACHMENT_GUEST'])) { ?>checked<?php } ?> /> <?php echo dr_lang('关闭'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('开启游客上传附件的权限'); ?></span>
                            </div>
                        </div>
                        <?php } ?>


                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('防止重复上传'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_CF]" value="1" <?php if ($data['SYS_ATTACHMENT_CF']) { ?>checked<?php } ?> /> <?php echo dr_lang('开启'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_CF]" value="0" <?php if (empty($data['SYS_ATTACHMENT_CF'])) { ?>checked<?php } ?> /> <?php echo dr_lang('关闭'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('当存在重复上传同一文件时，只存储一个文件'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('上传安全策略'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_SAFE]" value="0" <?php if (empty($data['SYS_ATTACHMENT_SAFE'])) { ?>checked<?php } ?> /> <?php echo dr_lang('严格模式'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" name="data[SYS_ATTACHMENT_SAFE]" value="1" <?php if ($data['SYS_ATTACHMENT_SAFE']) { ?>checked<?php } ?> /> <?php echo dr_lang('宽松模式'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('严格模式将对文件进行全面检测是否存在非法特征'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('全局存储策略'); ?></label>
                            <div class="col-md-9">
                                <label>
                                    <select name="data[SYS_ATTACHMENT_SAVE_ID]" class="form-control">
                                        <option value="0"> <?php echo dr_lang('按字段分别设置'); ?> </option>
                                        <?php if (is_array($remote)) { $key_t=-1;$count_t=dr_count($remote);foreach ($remote as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
                                        <option value="<?php echo $t['id']; ?>" <?php if ($data['SYS_ATTACHMENT_SAVE_ID'] == $t['id']) { ?> selected<?php } ?>> <?php echo dr_lang($t['name']); ?> </option>
                                        <?php } } ?>
                                    </select>
                                </label>
                                <span class="help-block"><?php echo dr_lang('设置全局存储时，全站附件上传都会存储到此存储策略中；按字段设置时，需要手动为每个字段设置不同的存储策略'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('存储目录方式'); ?></label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"><input type="radio" onclick="$('.dr_attachment_type').hide()" name="data[SYS_ATTACHMENT_SAVE_TYPE]" value="0" <?php if (empty($data['SYS_ATTACHMENT_SAVE_TYPE'])) { ?>checked<?php } ?> /> <?php echo dr_lang('默认目录'); ?> <span></span></label>
                                    <label class="mt-radio mt-radio-outline"><input type="radio" onclick="$('.dr_attachment_type').show()" name="data[SYS_ATTACHMENT_SAVE_TYPE]" value="1" <?php if ($data['SYS_ATTACHMENT_SAVE_TYPE']) { ?>checked<?php } ?> /> <?php echo dr_lang('自定义目录'); ?> <span></span></label>
                                </div>
                                <span class="help-block"><?php echo dr_lang('默认存储目录为：/年月/文件名'); ?></span>
                            </div>
                        </div>

                        <div <?php if (empty($data['SYS_ATTACHMENT_SAVE_TYPE'])) { ?>style="display: none"<?php } ?> class="form-group dr_attachment_type">
                        <label class="col-md-2 control-label"><?php echo dr_lang('存储目录格式'); ?></label>
                        <div class="col-md-9">
                            <label><input class="form-control input-xlarge" type="text" name="data[SYS_ATTACHMENT_SAVE_DIR]" value="<?php echo htmlspecialchars($data['SYS_ATTACHMENT_SAVE_DIR']); ?>" ></label>
                            <span class="help-block"><?php echo dr_lang('留空表示不要目录存储，可填参数格式：{y}表示年，{m}表示月，{d}表示日，/表示目录，不要填写其他特殊符号'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label"><?php echo dr_lang('附件上传目录'); ?></label>
                        <div class="col-md-9">
                            <div class="input-group input-xlarge">
                                <input class="form-control " type="text" id="dr_attachment_dir" name="data[SYS_ATTACHMENT_PATH]" value="<?php echo htmlspecialchars($data['SYS_ATTACHMENT_PATH']); ?>" >
                                <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_domain_dir('dr_attachment_dir')" type="button"><i class="fa fa-code"></i> <?php echo dr_lang('测试'); ?></button>
                                        </span>
                            </div>
                            <span class="help-block"><?php echo dr_lang('此目录必须有读写权限，绝对路径请以“/”开头'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><?php echo dr_lang('附件URL地址'); ?></label>
                        <div class="col-md-9">
                            <div class="input-group input-xlarge">
                                <input class="form-control " type="text" name="data[SYS_ATTACHMENT_URL]" value="<?php echo htmlspecialchars($data['SYS_ATTACHMENT_URL']); ?>" >
                                <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_domain()" type="button"><i class="fa fa-wrench"></i> <?php echo dr_lang('检测'); ?></button>
                                        </span>
                            </div>
                            <span class="help-block"><?php echo dr_lang('当设置了附件上传目录后，必须为该目录指定域名，用于分离附件，留空表示默认本站地址（站外保存时必须指定域名）'); ?></span>
                        </div>
                    </div>
                    <div class="form-group" style="display: none" id="dr_test_domain">
                        <label class="col-md-2 control-label"><?php echo dr_lang('目录检测结果'); ?></label>
                        <div class="col-md-9" style="padding-top: 3px; line-height: 25px; color:green" id="dr_test_domain_result">

                        </div>
                    </div>

                    </div>
                </div>

                <div class="tab-pane <?php if ($page==1) { ?>active<?php } ?>" id="tab_1">
                    <div class="form-body">



                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('缩略图存储目录'); ?></label>
                            <div class="col-md-9">
                                <div class="input-group input-xlarge">
                                    <input class="form-control " type="text" id="dr_cache_dir" name="image[cache_path]" value="<?php echo htmlspecialchars($image['cache_path']); ?>" >
                                    <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_domain_dir('dr_cache_dir')" type="button"><i class="fa fa-code"></i> <?php echo dr_lang('测试'); ?></button>
                                        </span>
                                </div>
                                <span class="help-block"><?php echo dr_lang('绝对路径请以“/”开头，默认uploadfile/thumb/'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('缩略图访问URL地址'); ?></label>
                            <div class="col-md-9">
                                <div class="input-group input-xlarge">
                                    <input class="form-control " id="dr_cache_url" type="text" name="image[cache_url]" value="<?php echo htmlspecialchars($image['cache_url']); ?>" >
                                    <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_thumb_domain()" type="button"><i class="fa fa-wrench"></i> <?php echo dr_lang('检测'); ?></button>
                                        </span>
                                </div>

                                <span class="help-block"><?php echo dr_lang('缩略图文件访问地址，可单独指定域名，默认/uploadfile/thumb/'); ?></span>
                            </div>
                        </div>

                        <div class="form-group" style="display: none" id="dr_test_thumb_domain">
                            <label class="col-md-2 control-label"><?php echo dr_lang('目录检测结果'); ?></label>
                            <div class="col-md-9" style="padding-top: 3px; line-height: 25px; color:green" id="dr_test_thumb_domain_result">

                            </div>
                        </div>



                    </div>
                </div>

                <div class="tab-pane <?php if ($page==2) { ?>active<?php } ?>" id="tab_2">
                    <div class="form-body">


                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('头像存储目录'); ?></label>
                            <div class="col-md-9">

                                <div class="input-group input-xlarge">
                                    <input class="form-control " type="text" id="dr_avatar_dir" name="image[avatar_path]" value="<?php echo htmlspecialchars($image['avatar_path']); ?>" >
                                    <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_domain_dir('dr_avatar_dir')" type="button"><i class="fa fa-code"></i> <?php echo dr_lang('测试'); ?></button>
                                        </span>
                                </div>
                                <span class="help-block"><?php echo dr_lang('绝对路径请以“/”开头，默认api/member/'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo dr_lang('头像访问URL地址'); ?></label>
                            <div class="col-md-9">
                                <div class="input-group input-xlarge">
                                    <input class="form-control " type="text" id="dr_avatar_url" name="image[avatar_url]" value="<?php echo htmlspecialchars($image['avatar_url']); ?>" >
                                    <span class="input-group-btn">
                                            <button class="btn blue" onclick="dr_test_avatar_domain()" type="button"><i class="fa fa-wrench"></i> <?php echo dr_lang('检测'); ?></button>
                                        </span>
                                </div>
                                <span class="help-block"><?php echo dr_lang('头像文件访问地址，可单独指定域名，默认/api/member/'); ?></span>
                            </div>
                        </div>

                        <div class="form-group" style="display: none" id="dr_test_avatar_domain">
                            <label class="col-md-2 control-label"><?php echo dr_lang('目录检测结果'); ?></label>
                            <div class="col-md-9" style="padding-top: 3px; line-height: 25px; color:green" id="dr_test_avatar_domain_result">

                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>


    <div class="portlet-body form myfooter">
        <div class="form-actions text-center">
            <label><button type="button" onclick="dr_ajax_submit('<?php echo dr_now_url(); ?>&page='+$('#dr_page').val(), 'myform', '2000')" class="btn blue"> <i class="fa fa-save"></i> <?php echo dr_lang('保存'); ?></button></label>
        </div>
    </div>
</form>

<script>
    function dr_test_domain() {
        // 延迟加载
        var loading = layer.load(2, {
            shade: [0.3,'#fff'], //0.1透明度的白色背景
            time: 5000
        });
        $('#dr_test_domain').hide();
        $.ajax({type: "POST",dataType:"json", url: admin_file+"?c=api&m=test_attach_domain", data: $('#myform').serialize(),
            success: function(json) {
                layer.close(loading);
                $('#dr_test_domain').show();
                $('#dr_test_domain_result').html(json.msg);
                return false;
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_admin_alert_error(HttpRequest, ajaxOptions, thrownError);
            }
        });
    }
    function dr_test_thumb_domain() {
        // 延迟加载
        var loading = layer.load(2, {
            shade: [0.3,'#fff'], //0.1透明度的白色背景
            time: 5000
        });
        $('#dr_test_domain').hide();
        $.ajax({type: "POST",dataType:"json", url: admin_file+"?c=api&m=test_thumb_domain", data: $('#myform').serialize(),
            success: function(json) {
                layer.close(loading);
                $('#dr_test_thumb_domain').show();
                $('#dr_test_thumb_domain_result').html(json.msg);
                return false;
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_admin_alert_error(HttpRequest, ajaxOptions, thrownError);
            }
        });
    }
    function dr_test_avatar_domain() {
        // 延迟加载
        var loading = layer.load(2, {
            shade: [0.3,'#fff'], //0.1透明度的白色背景
            time: 5000
        });
        $('#dr_test_domain').hide();
        $.ajax({type: "POST",dataType:"json", url: admin_file+"?c=api&m=test_avatar_domain", data: $('#myform').serialize(),
            success: function(json) {
                layer.close(loading);
                $('#dr_test_avatar_domain').show();
                $('#dr_test_avatar_domain_result').html(json.msg);
                return false;
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_admin_alert_error(HttpRequest, ajaxOptions, thrownError);
            }
        });
    }
    function dr_test_domain_dir(id) {
        $.ajax({type: "GET",dataType:"json", url: admin_file+"?c=api&m=test_attach_dir&v="+encodeURIComponent($("#"+id).val()),
            success: function(json) {
                dr_tips(json.code, json.msg, -1);
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_admin_alert_error(HttpRequest, ajaxOptions, thrownError)
            }
        });
    }
</script>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>