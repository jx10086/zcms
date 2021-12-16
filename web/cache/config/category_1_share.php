<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 站点栏目配置文件
 */

return array (
    1 => array (
        'id' => '1',
        'tid' => '1',
        'pid' => '0',
        'mid' => 'news',
        'pids' => '0',
        'name' => '新闻动态',
        'dirname' => 'xwdt',
        'pdirname' => '',
        'child' => 1,
        'disabled' => '0',
        'ismain' => '1',
        'childids' => '1,7,8',
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'linkurl' => '',
            'urlrule' => '0',
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{catpname}{join}{modname}{join}{SITE_NAME}',
                'list_keywords' => '',
                'list_description' => '',
            ),
            'template' =>         array (
                'pagesize' => '10',
                'mpagesize' => '10',
                'page' => 'page.html',
                'list' => 'list.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'show' => 'show.html',
            ),
        ),
        'displayorder' => '0',
    ),
    2 => array (
        'id' => '2',
        'tid' => '1',
        'pid' => '0',
        'mid' => 'news',
        'pids' => '0',
        'name' => '图片展示',
        'dirname' => 'tpzs',
        'pdirname' => '',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '1',
        'childids' => 2,
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'linkurl' => '',
            'urlrule' => '0',
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{catpname}{join}{modname}{join}{SITE_NAME}',
                'list_keywords' => '',
                'list_description' => '',
            ),
            'template' =>         array (
                'pagesize' => '10',
                'mpagesize' => '10',
                'page' => 'page.html',
                'list' => 'list.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'show' => 'show.html',
            ),
        ),
        'displayorder' => '0',
    ),
    3 => array (
        'id' => '3',
        'tid' => '0',
        'pid' => '0',
        'mid' => '',
        'pids' => '0',
        'name' => '关于我们',
        'dirname' => 'guanyuwomen',
        'pdirname' => '',
        'child' => 1,
        'disabled' => '0',
        'ismain' => '1',
        'childids' => '3,5,6',
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'linkurl' => '',
            'getchild' => '1',
            'urlrule' => '0',
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{catpname}{join}{modname}{join}{SITE_NAME}',
                'list_keywords' => '',
                'list_description' => '',
            ),
            'template' =>         array (
                'pagesize' => '10',
                'mpagesize' => '10',
                'page' => 'page.html',
                'list' => 'page.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'show' => 'show.html',
            ),
        ),
        'displayorder' => '0',
    ),
    4 => array (
        'id' => '4',
        'tid' => '2',
        'pid' => '0',
        'mid' => '',
        'pids' => '0',
        'name' => '技术支持',
        'dirname' => 'jishuzhichi',
        'pdirname' => '',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '1',
        'childids' => 4,
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'linkurl' => 'http://www.xunruicms.com',
            'urlrule' => '0',
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{catpname}{join}{modname}{join}{SITE_NAME}',
                'list_keywords' => '',
                'list_description' => '',
            ),
            'template' =>         array (
                'pagesize' => '10',
                'mpagesize' => '10',
                'page' => 'page.html',
                'list' => 'list.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'show' => 'show.html',
            ),
        ),
        'displayorder' => '0',
    ),
    5 => array (
        'id' => '5',
        'tid' => '0',
        'pid' => '3',
        'mid' => '',
        'pids' => '0,3',
        'name' => '公司介绍',
        'dirname' => 'gongsijieshao',
        'pdirname' => 'guanyuwomen/',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '0',
        'childids' => 5,
        'thumb' => '',
        'show' => '1',
        'content' => '<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 15px; padding: 0px; color: rgb(68, 68, 68); font-family: &quot;Open Sans&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">某某某信息技术有限公司成立于2014年3月，是一家专注于「为中小企业提供信息化服务」的互联网企业，主要从事PHP语言的CMS网站管理系统、线下通信信息工程、线上线下软件咨询与服务等业务。</p><p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 15px; padding: 0px; color: rgb(68, 68, 68); font-family: &quot;Open Sans&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">与其他建站公司不同的是，我们自创业之初就自主研发了一款免费的FineCMS、商业版POSCMS、迅睿开发框架，并且以迅睿为核心产品一直不断更新研发至今，经过多年的精心打造和发展，具有广泛的用户基础和稳定的程序框架，已成长为国内领先的CMS建站程序开发商和网站建设服务商。</p><p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 15px; padding: 0px; color: rgb(68, 68, 68); font-family: &quot;Open Sans&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">自创办以来，某某某信息技术有限公司一直保持高速发展，服务了超过500家客户，遍及全国31个省市，从PC时代的CMS，到移动时代的多终端一体化，某某某紧跟时代发展趋势。</p><p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 15px; padding: 0px; color: rgb(68, 68, 68); font-family: &quot;Open Sans&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">我们深知某某某信息技术有限公司的发展离不开广大客户长期以来的支持。始终以客户、员工、企业共赢为核心价值观，某某某信息技术有限公司团队将秉承这一服务理念，汇聚互联网行业精英，把握产业发展规律，坚持产品、技术和服务创新，打造顶级“互联网”解决方案。我们将始终秉承“诚信、专业、团结，创新，双赢，发展。”的企业精神，配合踏实的工作作风，竭诚为每位客户提供最优质的服务！&nbsp;</p><p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(68, 68, 68); font-family: &quot;Open Sans&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);"><span style="box-sizing: border-box; margin: 0px; padding: 0px; font-weight: 700;">企业使命：助力传媒产业互联网化<br/>企业愿景：成为互联网+媒体解决方案领导者<br/>企业价值观：客户第一 团队合作 与时俱进 诚信 专注 激情</span></p><p><br/></p>',
        'setting' =>     array (
            'edit' => 1,
            'template' =>         array (
                'list' => 'list.html',
                'show' => 'show.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'pagesize' => 20,
            ),
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}',
                'show_title' => '[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}',
            ),
        ),
        'displayorder' => '0',
    ),
    6 => array (
        'id' => '6',
        'tid' => '0',
        'pid' => '3',
        'mid' => '',
        'pids' => '0,3',
        'name' => '迅睿CMS框架',
        'dirname' => 'xunruicmskuangjia',
        'pdirname' => 'guanyuwomen/',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '0',
        'childids' => 6,
        'thumb' => '',
        'show' => '1',
        'content' => '<p>迅睿CMS是一款基于CodeIgniter4开发的内容管理框架，它只具备最基础的内容管理功能和最基础的用户管理权限，程序简洁轻量化设计，由系统框架+应用插件快速组建Web应用，发者可以根据自身的需求以应用插件的形式进行扩展，每个应用插件都能独立的完成自己的任务，也可通过系统调用其他应用插件进行协同工作。</p><p>迅睿CMS本身是非常简洁轻量化的程序，提供最基础的前端PC界面和移动端界面，后台管理操作采用自适应移动终端设计，无论你使用电脑、手机、平板都能快捷的操作和管理后台。每个应用插件都必须支持这种模式，满足多个终端的设计需求。</p><p>迅睿CMS其内核采用国外主流PHP开发框架CodeIgniter4，技术文档全面。我们在研发迅睿CMS时没有去破坏CodeIgniter本身的代码，可以说完全采用CodeIgniter的开发逻辑思路，开发者可以安全采用CodeIgniter官方提供的标准文档来进行二次开发。</p><p><br/></p><p><strong>效率与安全</strong></p><p>1、运用全新PHP7语法特性，设计时考虑到性能优化，运行效率高达4倍于PHP5系列开发环境<br/></p><p>2、运用CI框架的扩展性和路由模式，加上ZF框架强大丰富的中间件和扩展包，大大提高系统的扩展性能</p><p>3、Zend框架官方全部扩展包支持自由引入本系统，按需加载模式，最大限度地提高开发效率</p><p>4、利用ZF提供的与安全相关的组件，包括 SQL 注入、XSS、CSRF、垃圾邮件和密码暴力破解攻击</p><p>5、动态缓存技术让动态页面新增支持缓存，让采用动态页面模式的网站访问速度更快，效率更高</p><p>6、全站支持HTTPS传输协议，更安全，支持小程序数据请求的URL规范</p><p>7、表单增加“csrf_token”验证功能，防护更强</p><p><br/></p><p><br/></p><p>官网地址：<a href="http://www.xunruicms.com">http://www.xunruicms.com</a></p><p><br/></p>',
        'setting' =>     array (
            'edit' => 1,
            'template' =>         array (
                'list' => 'list.html',
                'show' => 'show.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'pagesize' => 20,
            ),
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}',
                'show_title' => '[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}',
            ),
        ),
        'displayorder' => '0',
    ),
    7 => array (
        'id' => '7',
        'tid' => '1',
        'pid' => '1',
        'mid' => 'news',
        'pids' => '0,1',
        'name' => '互联网',
        'dirname' => 'hulianwang',
        'pdirname' => 'xwdt/',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '0',
        'childids' => 7,
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'edit' => 1,
            'template' =>         array (
                'list' => 'list.html',
                'show' => 'show.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'pagesize' => 20,
            ),
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}',
                'show_title' => '[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}',
            ),
        ),
        'displayorder' => '0',
    ),
    8 => array (
        'id' => '8',
        'tid' => '1',
        'pid' => '1',
        'mid' => 'news',
        'pids' => '0,1',
        'name' => 'PHP技术',
        'dirname' => 'phpjishu',
        'pdirname' => 'xwdt/',
        'child' => 0,
        'disabled' => '0',
        'ismain' => '0',
        'childids' => 8,
        'thumb' => '',
        'show' => '1',
        'content' => '',
        'setting' =>     array (
            'edit' => 1,
            'template' =>         array (
                'list' => 'list.html',
                'show' => 'show.html',
                'category' => 'category.html',
                'search' => 'search.html',
                'pagesize' => 20,
            ),
            'seo' =>         array (
                'list_title' => '[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}',
                'show_title' => '[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}',
            ),
        ),
        'displayorder' => '0',
    ),
);