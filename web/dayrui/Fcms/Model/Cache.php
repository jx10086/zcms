<?php namespace Phpcmf\Model;
/**
 * www.xunruicms.com
 * 迅睿内容管理框架系统（简称：迅睿CMS）
 * 本文件是框架系统文件，二次开发时不可以修改本文件，可以通过继承类方法来重写此文件
 **/


// 系统缓存
class Cache extends \Phpcmf\Model
{
    protected $site_cache;
    protected $module_cache;
    protected $is_sync_cache;

    // 清理缩略图
    public function update_thumb() {
        list($cache_path) = dr_thumb_path();
        if (strpos(WEBPATH, $cache_path) !== false || is_file($cache_path.'index.php')) {
            // 防止误删除
            \Phpcmf\Service::C()->_json(0, dr_lang('缩略图目录异常，请手动清理：%s', $cache_path));
        }
        dr_dir_delete($cache_path);
        dr_mkdirs($cache_path);
        \Phpcmf\Service::C()->_json(1, dr_lang('清理完成'), 1);
    }

    // 更新附件缓存
    public function update_attachment() {

        $page = intval($_GET['page']);
        if (!$page) {
            dr_dir_delete(WRITEPATH.'attach');
            dr_mkdirs(WRITEPATH.'attach');
            /*不清理缩略图文件是因为静态页面会导致缩略图404的悲剧
            list($cache_path) = dr_thumb_path();
            dr_dir_delete($cache_path);
            dr_mkdirs($cache_path);*/
            \Phpcmf\Service::C()->_json(1, dr_lang('正在检查附件'), 1);
        }

        $total = $this->table('attachment')->counts();
        if (!$total) {
            \Phpcmf\Service::C()->_json(1, dr_lang('无可用附件更新'), 0);
        }

        $psize = 300;
        $tpage = ceil($total/$psize);
        $result = $this->db->table('attachment')->orderBy('id ASC')->limit($psize, $psize * ($page - 1))->get()->getResultArray();
        if ($result) {
            foreach ($result as $t) {
                \Phpcmf\Service::C()->get_attachment($t['id']);
            }
        }

        if ($page > $tpage) {
            \Phpcmf\Service::C()->_json(1, dr_lang('已更新%s个附件', $total), 0);
        }

        \Phpcmf\Service::C()->_json(1, dr_lang('正在更新中（%s/%s）', $page, $tpage), $page + 1);
    }

    // 同步更新缓存
    // \Phpcmf\Service::M('cache')->sync_cache(); $is_site 表示是否作为站点来更新缓存
    public function sync_cache($name = '', $namepspace = '', $is_site_param = 0) {

        if (!$this->is_sync_cache) {
            $this->site_cache = $this->table('site')->where('disabled', 0)->getAll();
            $this->module_cache = $this->table('module')->order_by('displayorder ASC,id ASC')->getAll();
            \Phpcmf\Service::M('site')->cache(0, $this->site_cache, $this->module_cache);
        }

        if ($name) {
            if (!$is_site_param) {
                // 普通缓存执行
                \Phpcmf\Service::M($name, $namepspace)->cache();
            } else {
                // 传入站点参数
                \Phpcmf\Service::M($name, $namepspace)->cache(SITE_ID);
            }
        }

        if ($this->module_cache) {
            \Phpcmf\Service::M('table')->cache(SITE_ID, $this->module_cache);
            \Phpcmf\Service::M('module')->cache(SITE_ID, $this->module_cache);
        }

        \Phpcmf\Service::M('menu')->cache();

        if (!$this->is_sync_cache) {
            $this->is_sync_cache = 1;
        }

        $this->update_data_cache();
    }

    // 更新缓存
    public function update_cache() {

        $site_cache = $this->table('site')->where('disabled', 0)->order_by('displayorder ASC,id ASC')->getAll();
        $module_cache = $this->table('module')->order_by('displayorder ASC,id ASC')->getAll();

        \Phpcmf\Service::M('site')->cache(0, $site_cache, $module_cache);

        // 全局缓存
        foreach (['auth', 'email', 'urlrule', 'member', 'attachment', 'system'] as $m) {
            \Phpcmf\Service::M($m)->cache();
        }

        // 按站点更新的缓存
        $cache = [];

        if (is_file(MYPATH.'/Config/Cache.php')) {
            $_cache = require MYPATH.'/Config/Cache.php';
            $_cache && $cache = dr_array22array($cache, $_cache);
        }

        // 执行插件自己的缓存程序
        $local = \Phpcmf\Service::Apps();
        $app_cache = [];
        foreach ($local as $dir => $path) {
            if (is_file($path.'install.lock')
                && is_file($path.'Config/Cache.php')) {
                $_cache = require $path.'Config/Cache.php';
                $_cache && $app_cache[$dir] = $_cache;
            }
        }

        foreach ($site_cache as $t) {

            \Phpcmf\Service::M('table')->cache($t['id'], $module_cache);
            \Phpcmf\Service::M('module')->cache($t['id'], $module_cache);

            foreach ($cache as $m => $namespace) {
                \Phpcmf\Service::M($m, $namespace)->cache($t['id']);
            }

            // 插件缓存
            $apps = [];
            if ($app_cache) {
                foreach ($app_cache as $namespace => $c) {
                    \Phpcmf\Service::C()->init_file($namespace);
                    foreach ($c as $i => $apt) {
                        $class = is_numeric($i) ? $apt : $i;
                        $apps[] = '['.$namespace.'-'.$class.']';
                        \Phpcmf\Service::M($class, $namespace)->cache($t['id']);
                    }
                }
            }

            // 记录日志
            CI_DEBUG && \Phpcmf\Service::L('input')->system_log('更新[站点#'.$t['id'].']缓存： '.implode(' - ', $apps));
        }

        \Phpcmf\Service::M('menu')->cache();

        $this->update_data_cache();
    }

    // 重建索引
    public function update_search_index() {

        $site_cache = $this->table('site')->where('disabled', 0)->getAll();
        $module_cache = $this->table('module')->getAll();
        if (!$module_cache) {
            return;
        }

        foreach ($site_cache as $t) {
            foreach ($module_cache as $m ) {
                $table = dr_module_table_prefix($m['dirname'], $t['id']);
                // 判断是否存在表
                if (!$this->db->tableExists($table)) {
                    continue;
                }
                $this->db->table($table.'_search')->truncate();
            }
        }
    }

    // 更新数据
    public function update_data_cache() {

        // 清空系统缓存
        \Phpcmf\Service::L('cache')->init()->clean();

        // 清空文件缓存
        \Phpcmf\Service::L('cache')->init('file')->clean();

        // 递归删除文件
        $path = [
            WRITEPATH.'file',
            WRITEPATH.'template',
            WRITEPATH.'debugbar',
        ];
        // 默认文件内容
        $cache_index = '<!DOCTYPE html>
<html>
<head>
    <title>403 Forbidden</title>
</head>
<body>

<p>Directory access is forbidden.</p>

</body>
</html>
';

        // 开发者模式下不删除temp目录
        if (!IS_DEV) {
            $path[] = WRITEPATH.'temp';
        }

        // 开始删除目录数据
        foreach ($path as $p) {
            dr_dir_delete($p);
            mkdir($p, 0777);
            file_put_contents($p.'/index.html', $cache_index);
        }

        // 删除缓存保留24小时内的文件
        $path = [
            WRITEPATH.'authcode',
            WRITEPATH.'debugbar',
            WRITEPATH.'session',
            WRITEPATH.'thread',
        ];
        foreach ($path as $p) {
            if ($fp = opendir($p)) {
                while (FALSE !== ($file = readdir($fp))) {
                    if ($file === '.' OR $file === '..'
                        OR $file === 'index.html'
                        OR $file[0] === '.'
                        OR !is_file($p.'/'.$file)
                        OR SYS_TIME - filemtime($p.'/'.$file) <  3600 * 24 // 保留24小时内的文件
                    ) {
                        continue;
                    }
                    unlink($p.'/'.$file);
                }
                file_put_contents($p.'/index.html', $cache_index);
            }
        }

        // 删除首页静态文件
        if (isset(\Phpcmf\Service::C()->site_info[SITE_ID]['SITE_INDEX_HTML'])
            && \Phpcmf\Service::C()->site_info[SITE_ID]['SITE_INDEX_HTML']) {
            unlink(\Phpcmf\Service::L('html')->get_webpath(SITE_ID, 'site', 'index.html'));
            unlink(\Phpcmf\Service::L('html')->get_webpath(SITE_ID, 'site', SITE_MOBILE_DIR.'/index.html'));
        }

        @unlink(WRITEPATH.'config/run_lock.php');

        // 重置Zend OPcache
        function_exists('opcache_reset') && opcache_reset();
    }

    // 重建子站配置文件
    public function update_site_config() {

        $site = [];
        $site_cache = $this->table('site')->where('disabled', 0)->getAll();
        foreach ($site_cache as $t) {
            $t['setting'] = dr_string2array($t['setting']);
            if ($t['id'] > 1 && $t['setting']['webpath']) {
                $rt = $this->update_webpath('Web', $t['setting']['webpath'], [
                    'SITE_ID' => $t['id'],
                    'FIX_WEB_DIR' => strpos($t['setting']['webpath'], '/') === false && strpos($t['domain'], $t['setting']['webpath']) !== false ? $t['setting']['webpath'] : '',
                ]);
                if ($rt) {
                    $this->_error_msg('站点['.$t['domain'].']: '.$rt);
                }
                $path = rtrim($t['setting']['webpath'], '/').'/';
            } else {
                $path = ROOTPATH;
            }
            if ($t['setting']['client']) {
                foreach ($t['setting']['client'] as $c) {
                    if ($c['name'] && $c['domain']) {
                        $rt = $this->update_webpath('Client', $path.$c['name'].'/', [
                            'CLIENT' => $c['name'],
                            'SITE_ID' => $t['id'],
                            'FIX_WEB_DIR' => $c['domain'] == $t['domain'].'/'.$c['name'] ? $c['name'] : '',
                            'SITE_FIX_WEB_DIR' => $t['id'] > 1 && $t['setting']['webpath'] && strpos($t['setting']['webpath'], '/') === false && strpos($t['domain'], $t['setting']['webpath']) !== false ? $t['setting']['webpath'] : '',
                        ]);
                        if ($rt) {
                            $this->_error_msg('站点['.$t['domain'].']的终端['.$c['name'].']: '.$rt);
                        }
                    }
                }
            }
            $site[] = $t['id'];
        }

        $cache = $this->table('module')->getAll();
        foreach ($cache as $t) {
            if (!is_file(APPSPATH.ucfirst($t['dirname']).'/Config/App.php')) {
                continue;
            } elseif ($t['share']) {
                continue;
            }
            $t['site'] = dr_string2array($t['site']);
            foreach ($site as $siteid) {
                if (isset($t['site'][$siteid]['domain']) && $t['site'][$siteid]['domain'] && $t['site'][$siteid] && $t['site'][$siteid]['webpath']) {
                    $rt = $this->update_webpath('Module_Domain', $t['site'][$siteid]['webpath'], [
                        'SITE_ID' => $siteid,
                        'MOD_DIR' => $t['dirname'],
                    ]);
                    if ($rt) {
                        $this->_error_msg('模块['.$t['site'][$siteid]['domain'].']: '.$rt);
                    }
                }
            }
        }
    }

    // 生成目录式手机目录
    public function update_mobile_webpath($path, $dirname) {

        foreach (['api.php', 'index.php'] as $file) {
            if (is_file(TEMPPATH.'Web/mobile/'.$file)) {
                $dst = $path.$dirname.'/'.$file;
                dr_mkdirs(dirname($dst));
                $size = file_put_contents($dst, str_replace([
                    '{FIX_WEB_DIR}'
                ], [
                    (defined('FIX_WEB_DIR') && FIX_WEB_DIR ? FIX_WEB_DIR.'/' : '').$dirname
                ], file_get_contents(TEMPPATH.'Web/mobile/'.$file)));
                if (!$size) {
                    return '文件['.$dst.']无法写入';
                }
            }
        }

        return;
    }

    // 更新站点
    public function update_webpath($name, $path, $value) {

        if (!$path) {
            return '目录为空';
        } elseif (strpos($path, ' ') === 0) {
            return '不能用空格开头';
        }

        $path = dr_get_dir_path($path);
        if (!$path) {
            return '目录为空';
        }

        dr_mkdirs($path);
        if (!is_dir($path)) {
            return '目录['.$path.']不存在';
        }

        // 创建入口文件
        //(defined('FIX_WEB_DIR') && FIX_WEB_DIR ? FIX_WEB_DIR.'/' : '').
        foreach ([
                     'admin.php',
                     'index.php',
                     'api.php',
                     'mobile/api.php',
                     'mobile/index.php',
                 ] as $file) {
            if (is_file(TEMPPATH.''.$name.'/'.$file)) {
                if ($file == 'admin.php') {
                    $dst = $path.(SELF == 'index.php' ? 'admin.php' : SELF);
                } else {
                    $dst = $path.$file;
                }
                $fix_web_dir = isset($value['FIX_WEB_DIR']) && $value['FIX_WEB_DIR'] ? $value['FIX_WEB_DIR'] : '';
                if (isset($value['SITE_ID']) && $value['SITE_ID'] > 1 && $fix_web_dir) {
                    // 移动端加二级
                    if (strpos($file, 'mobile/') !== false) {
                        $fix_web_dir.= '/mobile';
                    }
                    // 终端加二级
                    if ($name == 'Client') {
                        $fix_web_dir= (isset($value['SITE_FIX_WEB_DIR']) && $value['SITE_FIX_WEB_DIR'] ? $value['SITE_FIX_WEB_DIR'].'/' : '').$fix_web_dir;
                    }
                }
                dr_mkdirs(dirname($dst));
                $size = file_put_contents($dst, str_replace([
                    '{CLIENT}',
                    '{ROOTPATH}',
                    '{MOD_DIR}',
                    '{SITE_ID}',
                    '{FIX_WEB_DIR}'
                ], [
                    $value['CLIENT'],
                    ROOTPATH,
                    $value['MOD_DIR'],
                    $value['SITE_ID'],
                    $fix_web_dir
                ], file_get_contents(TEMPPATH.$name.'/'.$file)));
                if (!$size) {
                    return '文件['.$dst.']无法写入';
                }
            }
        }

        // 复制百度编辑器到当前目录
        $this->cp_ueditor_file($path);

        // 复制百度编辑器到移动端站点
        if (is_dir($path.'mobile')) {
            $this->cp_ueditor_file($path.'mobile/');
        }

        return '';
    }

    // 编辑器更新
    public function update_ueditor() {

        $site = $this->table('site')->where('disabled', 0)->getAll();
        foreach ($site as $t) {
            $t['setting'] = dr_string2array($t['setting']);
            if ($t['id'] > 1 && $t['setting']['webpath']) {
                $path = rtrim($t['setting']['webpath'], '/').'/';
            } else {
                $path = WEBPATH;
            }
            // 复制百度编辑器到当前目录
            $this->cp_ueditor_file($path);
            // 复制百度编辑器到移动端站点
            $this->cp_ueditor_file($path.'mobile/');
            if ($t['setting']['client']) {
                foreach ($t['setting']['client'] as $c) {
                    if ($c['name'] && $c['domain']) {
                        // 复制百度编辑器到当前目录
                        $this->cp_ueditor_file($path.$c['name'].'/');
                    }
                }
            }
        }

        $module = $this->table('module')->getAll();
        foreach ($module as $t) {
            if (!is_file(APPSPATH.ucfirst($t['dirname']).'/Config/App.php')) {
                continue;
            } elseif ($t['share']) {
                continue;
            }
            $t['site'] = dr_string2array($t['site']);
            foreach ($site as $r) {
                $siteid = $r['id'];
                if (isset($t['site'][$siteid]['domain']) && $t['site'][$siteid]['domain'] && $t['site'][$siteid] && $t['site'][$siteid]['webpath']) {
                    $path = rtrim($t['site'][$siteid]['webpath'], '/').'/';
                    // 复制百度编辑器到当前目录
                    $this->cp_ueditor_file($path);
                    // 复制百度编辑器到移动端站点
                    $this->cp_ueditor_file($path.'mobile/');
                }
            }
        }

        // 为后台域名移动编辑器目录
        if (dr_is_app('safe')) {
            $safe = \Phpcmf\Service::M('app')->get_config('safe');
            if ($safe) {
                foreach ($safe as $key => $path) {
                    if (is_string($path) && is_numeric($key) && is_dir($path)) {
                        $this->cp_ueditor_file($path.'/');
                    }
                }
            }
        }
    }

    // 复制编辑器
    public function cp_ueditor_file($path) {

        $npath = $path.'api/ueditor/';
        if (!is_file($npath.'ueditor.config.js')) {
            return;
        }

        dr_mkdirs($npath);

        \Phpcmf\Service::L('file')->copy_dir(ROOTPATH.'api/ueditor/dialogs/', ROOTPATH.'api/ueditor/dialogs/', $npath.'dialogs/');
        \Phpcmf\Service::L('file')->copy_dir(ROOTPATH.'api/ueditor/third-party/', ROOTPATH.'api/ueditor/third-party/', $npath.'third-party/');
    }

    // 错误输出
    protected function _error_msg($msg) {
        echo dr_array2string(dr_return_data(0, $msg));exit;
    }

}