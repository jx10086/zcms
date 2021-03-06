<?php namespace Phpcmf\Model;
/**
 * www.xunruicms.com
 * 迅睿内容管理框架系统（简称：迅睿CMS）
 * 本文件是框架系统文件，二次开发时不可以修改本文件，可以通过继承类方法来重写此文件
 **/

// 模型类

class Urlrule extends \Phpcmf\Model
{


    // 缓存
    public function cache($site = SITE_ID) {

        $data = $this->table('urlrule')->getAll();
        $cache = [];
        if ($data) {
            foreach ($data as $t) {
                $t['value'] = dr_string2array($t['value']);
                $cache[$t['id']] = $t;
            }
        }

        \Phpcmf\Service::L('cache')->set_file('urlrule', $cache);
        return;
    }
    
}