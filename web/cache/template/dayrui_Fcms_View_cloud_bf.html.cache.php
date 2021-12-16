<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p>本功能用于检测本地核心文件与服务器最新版文件的差异</p>
    <p>本次检测中的文件强烈建议开发者不要去修改，否则会引起系统不稳定或者系统崩溃</p>
    <p>如果二次开发中需要变更核心文件的逻辑，需要提前向官方说明，再为你单独开放钩子或继承类，可提交建议类工单或者邮件finecms@qq.com</p>
</div>

<div class="text-center">
    <button type="button" id="dr_check_button" onclick="dr_checking();" class="btn blue"> <i class="fa fa-refresh"></i> 立即与服务器文件对比差异</button>
</div>

<div id="dr_check_result" class="margin-top-30" style="display: none">

</div>

<div id="dr_check_div"  class="well margin-top-30" style="display: none">
    <div class="scroller" style="height:300px" data-rail-visible="1"  id="dr_check_html">

    </div>
</div>

<div id="dr_check_ing" style="display: none">
    <div class="progress progress-striped">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">

        </div>
    </div>
</div>

<div class="portlet light bordered" style="margin-top: 30px;">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject  sbold ">对比结果</span>
        </div>

    </div>
    <div class="portlet-body">
        <div id="dr_check_bf">

        </div>
    </div>
</div>

<input id="dr_check_status" type="hidden" value="0">

<script>
    function dr_checking() {
        $('#dr_check_button').attr('disabled', true);
        $('#dr_check_button').html('<i class="fa fa-refresh"></i> 准备中');
        $('#dr_check_bf').html("");
        $('#dr_check_html').html("正在准备中");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "<?php echo dr_url('cloud/bf_count'); ?>",
            success: function (json) {
                if (json.code == 0) {
                    dr_tips(0, json.msg);
                    $('#dr_check_div').show();
                    $('#dr_check_result').show();
                    $('#dr_check_button').attr('disabled', false);
                    $('#dr_check_button').html('<i class="fa fa-refresh"></i> 重新对比');
                    $('#dr_check_html').append('<p style="color: red">'+json.msg+'</p>');
                } else {
                    $('#dr_check_bf').html("");
                    $('#dr_check_html').html("");
                    $('#dr_check_result').html($('#dr_check_ing').html());
                    $('#dr_check_div').show();
                    $('#dr_check_result').show();
                    $('#dr_check_button').attr('disabled', true);
                    $('#dr_check_bf').append('<p style="color: green">本网站程序下载时间：<?php echo $cms_version['downtime']; ?></p>');
                    $('#dr_check_bf').append('<p style="color: green">服务端最近更新时间：'+json.msg+'</p>');
                    dr_ajax2ajax(1);
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_alert_error(HttpRequest, this, thrownError);
            }
        });
    }
    function dr_ajax2ajax(page) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "<?php echo dr_url('cloud/bf_check'); ?>&page="+page,
            success: function (json) {

                $('#dr_check_html').append(json.msg);
                document.getElementById('dr_check_html').scrollTop = document.getElementById('dr_check_html').scrollHeight;

                if (json.code == 0) {
                    $('#dr_check_button').attr('disabled', false);
                    $('#dr_check_button').html('<i class="fa fa-refresh"></i> 重新对比');
                    dr_tips(0, '发现异常');
                } else {
                    $('#dr_check_result .progress-bar-success').attr('style', 'width:'+json.code+'%');
                    if (json.code == 100) {
                        $('#dr_check_button').attr('disabled', false);
                        $('#dr_check_button').html('<i class="fa fa-refresh"></i> 重新对比');
                        // 对比结果
                        var isxs = 0;
                        $("#dr_check_html .rbf").each(function(){
                            $('#dr_check_bf').append('<p>'+$(this).html()+'</p>');
                            isxs = 1;
                        });
                        if (isxs == 1) {
                            $('#dr_check_bf').append('<p style="text-align: center"><a class="btn green" href="http://www.xunruicms.com/member.html?app=vip&c=home&m=index" target="_blank">前往下载升级包，然后手动替换以上红色的文件</a></p>');
                        }
                    } else {
                        $('#dr_check_button').html('<i class="fa fa-refresh"></i> 文件对比中 '+json.code+'%');
                        dr_ajax2ajax(json.code);
                    }
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                dr_ajax_alert_error(HttpRequest, this, thrownError);
            }
        });
    }
</script>


<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>