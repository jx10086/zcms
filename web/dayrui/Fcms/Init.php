<?php
/**
 * www.xunruicms.com
 * 迅睿内容管理框架系统（简称：迅睿CMS）
 * 本文件是框架系统文件，二次开发时不可以修改本文件
 *
 * (c) 四川迅睿云软件开发有限公司 <q@xunruicms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 **/

use CodeIgniter\Config\DotEnv;
use Config\App;
use Config\Autoload;
use Config\Modules;
use Config\Services;

// CI框架目录
!defined('BASEPATH') && define('BASEPATH', FCPATH.'System/');
define('SYSTEMPATH', BASEPATH);
// CMS公共程序目录
define('CMSPATH', FCPATH.'Fcms/');
// 核心程序目录
define('COREPATH', CMSPATH);
// App程序目录
!defined('APPSPATH') && define('APPSPATH', FCPATH.'App/');
// 程序初始化
!defined('MYPATH') && define('MYPATH', FCPATH.'My/');
// 定义模板目录
!defined('TPLPATH') && define('TPLPATH', ROOTPATH.'template/');
// 是否可编辑后模板
!defined('IS_EDIT_TPL') && define('IS_EDIT_TPL', 0);
// 是否启用高级开发模式
!defined('IS_XRDEV') && define('IS_XRDEV', 0);
// 最大栏目数量限制category
!defined('MAX_CATEGORY') && define('MAX_CATEGORY', 200);
// 定义移动端模板目录
!defined('MOBILE_TPL_DIR') && define('MOBILE_TPL_DIR', 'mobile');
// 编辑器的图片的title和alt默认占位字符
!defined('UEDITOR_IMG_TITLE') && define('UEDITOR_IMG_TITLE', '{xunruicms_img_title}');
// tests
define('TESTPATH', WRITEPATH.'tests/');
// temp目录
define('TEMPPATH', CMSPATH.'Temp/');
// COMPOSER文件
define('COMPOSER_PATH', is_file(FCPATH . 'Vendor/autoload.php') ? FCPATH . 'Vendor/autoload.php' : FCPATH . 'vendor/autoload.php');

// 是否来自ajax提交
define('IS_AJAX', (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
// 是否来自post提交
define('IS_POST', isset($_POST) && count($_POST) ? TRUE : FALSE);

define('IS_AJAX_POST', IS_POST);
// 当前系统时间戳
define('SYS_TIME', $_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time());

// 系统变量
$system = [
    'SYS_DEBUG' => 0,
    'SYS_ADMIN_CODE' => 0,
    'SYS_ADMIN_LOG' => 0,
    'SYS_AUTO_FORM' => 0,
    'SYS_ADMIN_PAGESIZE' => 10,
    'SYS_CRON_AUTH' => '',
    'SYS_SMS_IMG_CODE' => 0,
    'SYS_GO_404' => 0,
    'SYS_301' => 0,
    'SYS_NOT_UPDATE' => 0,
    'SYS_URL_PREG' => 0,
    'SYS_KEY' => 'xunruicms',
    'SYS_CSRF'  => 1,
    'SYS_HTTPS' => 0,
    'SYS_ADMIN_MODE'  => 0,
    'SYS_ADMIN_LOGINS'  => 0,
    'SYS_ADMIN_LOGIN_TIME'  => 0,
    'SYS_ADMIN_OAUTH'    => 0,
    'SYS_ATTACHMENT_DB'     => 1,
    'SYS_ATTACHMENT_GUEST'   => 0,
    'SYS_ATTACHMENT_CF'   => 0,
    'SYS_ATTACHMENT_PATH'   => '',
    'SYS_ATTACHMENT_SAVE_TYPE'  => '',
    'SYS_ATTACHMENT_SAVE_DIR'   => '',
    'SYS_ATTACHMENT_SAVE_ID'   => '',
    'SYS_ATTACHMENT_URL'    => '',
    'SYS_AVATAR_PATH'   => '',
    'SYS_AVATAR_URL'    => '',
    'SYS_API_CODE'  => '',
    'SYS_THEME_ROOT'    => '',
    'SYS_ATTACHMENT_SAFE'    => '',
];
if (is_file(WRITEPATH.'config/system.php')) {
    $my = require WRITEPATH.'config/system.php';
} else {
    $my = [];
}

// 强制禁用301
if (defined('IS_NOT_301') && IS_NOT_301) {
    $my['SYS_301'] = 1;
}

foreach ($system as $var => $value) {
    if (!defined($var)) {
        define($var, isset($my[$var]) ? $my[$var] : $value);
    }
}
unset($my, $system);

!defined('IS_ADMIN_CACHE') && define('IS_ADMIN_CACHE', '1');
!defined('SQGS') && define('SQGS', '迅睿CMS开源框架');
!defined('CI_DEBUG') && define('CI_DEBUG', IS_DEV ? 1 : IS_ADMIN && SYS_DEBUG);
!defined('IS_FB_DEBUG') && define('IS_FB_DEBUG', CI_DEBUG ? 0 : 1);

// 显示错误提示
IS_ADMIN || IS_DEV ? error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT ^ E_DEPRECATED) : error_reporting(0);

// 显示错误提示
if (CI_DEBUG) {
    ini_set('display_errors', 1);
    // 重置Zend OPcache
    function_exists('opcache_reset') && opcache_reset();
    define('ENVIRONMENT', 'development');
} else {
    ini_set('display_errors', 0);
}

// 缓存变量
$cache = [];
if (is_file(WRITEPATH.'config/cache.php')) {
    $cache = require WRITEPATH.'config/cache.php';
    IS_DEV && $cache['SYS_CACHE'] = 0; // 开发者模式下关闭缓存
}
foreach ([
             'SYS_CACHE',
             'SYS_CACHE_TYPE',
             'SYS_CACHE_SHOW',
             'SYS_CACHE_PAGE',
             'SYS_CACHE_LIST',
             'SYS_CACHE_SEARCH',
             'SYS_CACHE_SMS',
         ] as $name) {
    define($name, floatval($cache[$name]));
}
unset($cache);

// 自定义开发目录分布
if (IS_XRDEV && is_file(MYPATH.'Dev.php')) {
    require MYPATH.'Dev.php';
} else {
    // 判断是否是app目录
    function dr_is_app_dir($name) {
        if (!$name) {
            return false;
        }
        return is_dir(APPSPATH.ucfirst($name));
    }
    function dr_get_app_dir($name) {
        if (!$name) {
            return false;
        }
        return APPSPATH.ucfirst($name).'/';
    }
    function dr_get_app_tpl($name = '') {
        return TPLPATH;
    }
    function dr_get_app_list() {
        return APPSPATH;
    }
    function dr_get_app_css($name) {
        return THEME_PATH.$name.'/';
    }
    function dr_get_app_css_dir($name) {
        return WEBPATH.'static/'.$name.'/';
    }
}

// 兼容错误提示
function dr_show_error($msg) {

    if (CI_DEBUG) {
        $url = '<p>'.FC_NOW_URL.'</p>';
    } else {
        $url = '';
        $msg = '您的系统遇到了故障，请联系管理员处理';
        http_response_code(404);
    }

    if (IS_AJAX) {
        $msg = json_encode([
            'code' => 0,
            'msg' => $msg
        ],JSON_UNESCAPED_UNICODE);
        if (isset($_GET['callback']) && $_GET['callback']) {
            echo $_GET['callback'].'('.$msg.')';exit;
        } else {
            echo $msg;exit;
        }
    } else {
        exit("<!DOCTYPE html><html lang=\"zh-cn\"><head><meta charset=\"utf-8\"><title>系统错误</title><style>        div.logo {            height: 200px;            width: 155px;            display: inline-block;            opacity: 0.08;            position: absolute;            top: 2rem;            left: 50%;            margin-left: -73px;        }        body {            height: 100%;            background: #fafafa;            font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;            color: #777;            font-weight: 300;        }        h1 {            font-weight: lighter;            letter-spacing: 0.8;            font-size: 3rem;            margin-top: 0;            margin-bottom: 0;            color: #222;        }        .wrap {            max-width: 1024px;            margin: 5rem auto;            padding: 2rem;            background: #fff;            text-align: center;            border: 1px solid #efefef;            border-radius: 0.5rem;            position: relative;        }        pre {            white-space: normal;            margin-top: 1.5rem;        }        code {            background: #fafafa;            border: 1px solid #efefef;            padding: 0.5rem 1rem;            border-radius: 5px;            display: block;        }        p {            margin-top: 1.5rem;        }        .footer {            margin-top: 2rem;            border-top: 1px solid #efefef;            padding: 1em 2em 0 2em;            font-size: 85%;            color: #999;        }        a:active,        a:link,        a:visited {            color: #dd4814;        }</style></head><body><div class=\"wrap\"><p>{$msg}</p>    {$url}</div></body></html>");
    }
}

/*
 * 重写config函数，防止modules被加载
 */
function config ($name, $getShared = true) {

    if ($name == 'Modules') {
        $name = 'Config\Modules';
    }

    return \CodeIgniter\Config\Config::get($name, $getShared);
}

/**
 * 全局返回消息
 */
function dr_exit_msg($code, $msg, $data = []) {

    ob_end_clean();

    $rt = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];

    if (isset($_GET['callback'])) {
        // jsonp
        header('HTTP/1.1 200 OK');
        echo ($_GET['callback'] ? $_GET['callback'] : 'callback').'('.json_encode($rt, JSON_UNESCAPED_UNICODE).')';
    } else if (($_GET['is_ajax'] || (defined('IS_API_HTTP') && IS_API_HTTP) || IS_AJAX)) {
        // json
        header('HTTP/1.1 200 OK');
        echo json_encode($rt, JSON_UNESCAPED_UNICODE);
    } else {
        // html
        dr_show_error($msg);
    }
    exit;
}

/*
 * 函数是否被启用
 */
if (!function_exists('locale_set_default')) {
    function locale_set_default($a) { }
}

/*
 * 重写日志记录函数
 */
function log_message($level, $message, array $context = []) {
    return \Phpcmf\Service::Log($level, $message, $context);
}

/*
 * 重写is_cli
 */
function is_cli(): bool {
    if (stripos(PHP_SAPI, 'cli') !== false || defined('STDIN')) {
        return true;
    }
    return false;
}

if (is_cli()) {
    // CLI命令行模式
     define('ADMIN_URL', 'http://localhost/');
     define('FC_NOW_URL', 'http://localhost/');
     define('FC_NOW_HOST', 'http://localhost/');
     define('DOMAIN_NAME', 'http://localhost/');
	 define('WEB_DIR', '/');
    if ($_SERVER["argv"]) {
        foreach ($_SERVER["argv"] as $val) {
            if (strpos($val, '=') !== false) {
                list($name) = explode('=', $val);
                $_GET[$name] = substr($val, strlen($name)+1);
            }
        }
    }
    defined('ENVIRONMENT') && define('ENVIRONMENT', 'testing');
} else {
    // 正常访问模式
    // 当前URL
    $url = 'http';
    if ((!IS_ADMIN && isset($system['SYS_HTTPS']) && $system['SYS_HTTPS'])
        || (defined('IS_HTTPS_FIX') && IS_HTTPS_FIX)
        || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        || (isset($_SERVER['HTTP_FROM_HTTPS']) && $_SERVER['HTTP_FROM_HTTPS'] == 'on')
        || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) != 'off')
    ) {
        $url.= 's';
    }
	$host = strtolower($_SERVER['HTTP_HOST']);
    if (strpos($host, ':') !== false) {
        list($nhost, $port) = explode(':', $host);
        if ($port == 80) {
            $host = $nhost; // 排除80端口
        }
    }
    $url.= '://'.$host;
    IS_ADMIN && define('ADMIN_URL', $url.'/'); // 优先定义后台域名
    define('FC_NOW_URL', $url.($_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']));
    define('FC_NOW_HOST', $url.'/'); // 域名部分
    define('DOMAIN_NAME', $host); // 当前域名
    
    // 伪静态字符串
    $uu = isset($_SERVER['HTTP_X_REWRITE_URL']) || trim($_SERVER['REQUEST_URI'], '/') == SELF ? trim($_SERVER['HTTP_X_REWRITE_URL'], '/') : ($_SERVER['REQUEST_URI'] ? trim($_SERVER['REQUEST_URI'], '/') : NULL);
    if (defined('FIX_WEB_DIR') && FIX_WEB_DIR && strpos($uu, FIX_WEB_DIR) !== false &&  strpos($uu, FIX_WEB_DIR) === 0) {
        $uu = trim(substr($uu, strlen(FIX_WEB_DIR)), '/');
        define('WEB_DIR', '/'.trim(FIX_WEB_DIR, '/').'/');
    } else {
        define('WEB_DIR', '/');
    }

    // 以index.php或者?开头的uri不做处理
    $uri = strpos($uu, SELF) === 0 || strpos($uu, '?') === 0 ? '' : $uu;

    // 当前URI
    define('CMSURI', $uri);

    // 根据自定义URL规则来识别路由
    if (!IS_ADMIN && $uri && !defined('IS_API') && !defined('FIX_WEB_URL')) {
        // 自定义URL解析规则
        $routes = [];
        $routes['index\.html(.*)'] = 'index.php?c=home&m=index';
        $routes['404\.html(.*)'] = 'index.php?&c=home&m=s404&uri='.$uri;
        $routes['rewrite-test.html(.*)'] = 'index.php?s=api&c=rewrite&m=test';
        if (is_file(ROOTPATH.'config/rewrite.php')) {
            $my = require ROOTPATH.'config/rewrite.php';
            $my && $routes = array_merge($routes, $my);
        }
        // 正则匹配路由规则
        $is_404 = 1;
        foreach ($routes as $key => $val) {
            $rewrite = $match = []; //(defined('SYS_URL_PREG') && SYS_URL_PREG ? '' : '$')
            if ($key == $uri || preg_match('/^'.$key.'$/U', $uri, $match)) {
                unset($match[0]);
                // 开始匹配
                $is_404 = 0;
                // 开始解析路由 URL参数模式
                $_GET = [];
                $queryParts = explode('&', str_replace(['index.php?', '/index.php?'], '', $val));
                if ($queryParts) {
                    foreach ($queryParts as $param) {
                        $item = explode('=', $param);
                        $_GET[$item[0]] = $item[1];
                        if (strpos($item[1], '$') !== FALSE) {
                            $id = (int)substr($item[1], 1);
                            $_GET[$item[0]] = isset($match[$id]) ? $match[$id] : $item[1];
                        }
                    }
                }
                !$_GET['c'] && $_GET['c'] = 'home';
                !$_GET['m'] && $_GET['m'] = 'index';
                // 结束匹配
                break;
            }
        }
        // 自定义路由模式
        if (is_file(ROOTPATH.'config/router.php')) {
            require ROOTPATH.'config/router.php';
        }
        // 说明是404
        if ($is_404) {
            $_GET['s'] = '';
            $_GET['c'] = 'home';
            $_GET['m'] = 's404';
            $_GET['uri'] = $uri;
        }
    }
}

// API接口项目标识 放到后面是为了识别api 的伪静态
!defined('IS_API') && define('IS_API', isset($_GET['s']) && $_GET['s'] == 'api');

// 解析自定义域名
if (!IS_API && $_GET['s'] != 'api' && is_file(WRITEPATH.'config/domain_app.php')){
    $domain = require WRITEPATH.'config/domain_app.php';
    // 强制定义为模块
    if (isset($domain[DOMAIN_NAME]) && $domain[DOMAIN_NAME] && is_dir(APPSPATH.ucfirst($domain[DOMAIN_NAME]))) {
        $_GET['s'] = $domain[DOMAIN_NAME];
    }
    unset($domain);
}

// 是否使用用户系统
define('IS_USE_MEMBER',  is_file(dr_get_app_dir('member').'/install.lock') ? dr_get_app_dir('member') : '');

// 判断s参数,“应用程序”文件夹目录
if (!IS_API && isset($_GET['s']) && preg_match('/^[a-z_]+$/i', $_GET['s'])) {
    // 判断会员模块,排除后台调用
    $dir = ucfirst($_GET['s']);
    if (!IS_ADMIN && $dir == 'Member') {
        // 用户系统
        if (!IS_USE_MEMBER) {
            dr_show_error(CI_DEBUG ? '用户系统插件未安装' : '无权限使用用户系统');
        }
        if ($_GET['app'] && dr_is_app_dir($_GET['app'])) {
            // 模块应用
            define('APPPATH', dr_get_app_dir($_GET['app']));
            define('APP_DIR', strtolower($_GET['app'])); // 应用目录名称
        } else {
            // 表示会员模块
            define('APPPATH', IS_USE_MEMBER);
            define('APP_DIR', 'member'); // 模块目录名称
        }
        define('IS_MEMBER', TRUE);
    } elseif (dr_is_app_dir($dir)) {
        // 模块应用
        define('APPPATH', dr_get_app_dir($dir));
        define('APP_DIR', strtolower($dir)); // 应用目录名称
        define('IS_MEMBER', FALSE);
    } else {
        // 不存在的应用
        dr_show_error(CI_DEBUG ? '应用程序('.dr_get_app_dir($dir).')不存在' : '应用程序('.strtolower($dir).')不存在');
    }
} else {
    // 系统主目录
    !defined('APPPATH') && define('APPPATH', COREPATH);
    !defined('APP_DIR') && define('APP_DIR', '');
    define('IS_MEMBER', FALSE);
}


/******* CodeIgniter Bootstrap *******/

// 自定义函数库
if (is_file(ROOTPATH.'config/custom.php')) {
    require ROOTPATH.'config/custom.php';
}
if (is_file(MYPATH.'Helper.php')) {
    require MYPATH.'Helper.php';
}
// 系统函数库
require CMSPATH.'Core/Helper.php';

// 定义常量
require COREPATH.'Config/Constants.php';

require BASEPATH.'Common.php';

// 自动加载机制
require SYSTEMPATH . 'Config/AutoloadConfig.php';
require COREPATH . 'Config/Autoload.php';
require SYSTEMPATH . 'Modules/Modules.php';
require COREPATH . 'Config/Modules.php';

require SYSTEMPATH . 'Autoloader/Autoloader.php';
require SYSTEMPATH . 'Config/BaseService.php';
require SYSTEMPATH . 'Config/Services.php';
require COREPATH . 'Config/Services.php';

require SYSTEMPATH . 'Events/Events.php';
require CMSPATH.'Core/Service.php';
require CMSPATH.'Core/Hooks.php';

class_alias('Config\Services', 'CodeIgniter\Services');

$loader = Services::autoloader();
$auto = new Autoload();

// 应用插件的自动识别
$auto = \Phpcmf\Service::Auto($auto);

$loader->initialize($auto, new Modules())->register();

if (is_file(COMPOSER_PATH)) {
    require_once COMPOSER_PATH;
}

require BASEPATH . 'Config/DotEnv.php';

$env = new DotEnv(COREPATH);
$env->load();

helper('url');

$app = new \Phpcmf\Extend\CodeIgniter(new App());
$app->initialize();
$app->run();