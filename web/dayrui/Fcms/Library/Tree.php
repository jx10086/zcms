<?php namespace Phpcmf\Library;
/**
 * www.xunruicms.com
 * 迅睿内容管理框架系统（简称：迅睿CMS）
 * 本文件是框架系统文件，二次开发时不可以修改本文件，可以通过继承类方法来重写此文件
 **/

class Tree {

    protected $data;
    protected $result_array;
    protected $icon;
    protected $nbsp = "{spacer}";
    protected $nbsp_str;
    protected $deep = 1;
    protected $ret;
    protected $cache;
    protected $result;
    protected $ismain = 0;

    // 初始化函数
    public function __construct() {
        $this->icon();
    }

    // 释放变量
    public function __destruct()
    {
        unset($this->data);
        unset($this->cache);
        unset($this->ret);
        unset($this->icon);
        unset($this->result_array);
        unset($this->nbsp_str);
        unset($this->nbsp);
        unset($this->result);
    }

    /**
     * 设置html标签
     */
    public function html_icon() {
        $this->nbsp_str = '<span class="tree-icon"></span>';
        $this->icon = [
            $this->nbsp_str,
            '<span class="tree-icon">├&nbsp;</span>',
            '<span class="tree-icon">└&nbsp;</span>'
        ];
        return $this;
    }

    /**
     * 设置普通标签
     */
    public function icon() {
        $this->nbsp_str = '&nbsp;';
        $this->icon = [
            $this->nbsp_str,
            '├&nbsp;',
            '└&nbsp;'
        ];
        return $this;
    }

    /**
     * 初始化类
     */
    public function init($arr) {
        $this->ret = '';
        $this->data = $arr;
        $this->result = [];
        return $this;
    }

    // 创建数据
    public function get($data) {
        $this->data = $data;
        $this->result = [];
        $this->create(0);
        return $this->result_array;
    }

    // 设置数据
    protected function _data($data) {
        $this->ret = '';
        $this->data = $data;
        $this->deep = 1;
        return $this;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    protected function get_child($k_id) {
        
        $arrays = [];

        /*
        if ($k_id && isset($this->data[$k_id]) && $this->data[$k_id] && $this->data[$k_id]['next_ids']) {
            if (is_array($this->data[$k_id]['next_ids'])) {
                foreach ($this->data[$k_id]['next_ids'] as $id) {
                    $arrays[$id] = $this->data[$id];
                }
            }
        }

        if (!$k_id && is_array($this->data)) {
            foreach($this->data as $id => $a) {
                if ($a['pid'] == $k_id) {
                    $arrays[$id] = $a;
                }
            }
        }
        */

        if (is_array($this->data)) {
            foreach($this->data as $id => $a) {
                if ($a['pid'] == $k_id) {
                    $arrays[$id] = $a;
                }
            }
        }

        $this->deep++;
        
        return $arrays;
    }

    /**
     * 得到树型数组
     */
    public function create($k_id = 0, $adds = '') {
        
        if ($this->deep > 5000) {
            return; // 防止死循环
        }

        $child = $this->get_child($k_id); // 获取子数据
        $number = 1;
        
        if (is_array($child)) {
            $total = dr_count($child);
            foreach($child as $id => $a) {
                $k = $adds ? $this->nbsp : '';
                $j = $number == $total ? $this->icon[2] : $this->icon[1];
                $a['spacer'] = $this->_get_spacer($adds ? $adds.$j : '');
                $this->result_array[] = $a;
                $this->create($a['id'], $adds.$k.$this->nbsp);
                $number++;
            }
        }
        
        $this->deep = 1;
    }

    // 替换空格填充符号
    protected function _get_spacer($str) {
        $num = substr_count($str, $this->nbsp) * 2;
        if ($num) {
            $str = str_replace($this->nbsp, '', $str);
            for ($i = 0; $i < $num; $i ++) {
                $str = $this->nbsp_str.$str;
            }

        }
        return $str;
    }

    // 替换逗号
    protected function _have($list, $item){
        return(strpos(',,'.$list.',', ','.$item.','));
    }

    /**
     * 用于栏目选择框
     *
     * @param integer	$myid	要查询的ID
     * @param string	$str	HTML代码方式
     * @param integer	$sid	默认选中
     * @param integer	$adds	前缀
     */
    protected function _linkage_tree_result($myid, $str, $sid = 0, $adds = '') {

        if ($this->deep > 5000) {
            return $this->ret; // 防止死循环
        }

        $number = 1;
        $mychild = $this->get_child($myid);

        if (is_array($mychild)) {

            $total = count($mychild);
            foreach ($mychild as $id => $a) {

                $j = $k = '';
                if ($number == $total) {
                    $j.= $this->icon[2];
                } else {
                    $j.= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }

                $spacer = $this->_get_spacer($adds ? $adds.$j : '');
                $selected = $this->_have($sid, $id) ? 'selected' : '';
                extract($a);

                eval("\$this->ret.= \"$str\";");
                
                $number++;

                // 如果有下级菜单就递归
                $a['child'] && $this->_linkage_tree_result($id, $str, $sid, $adds.$k.$this->nbsp);
            }
        }

        return $this->ret;
    }


    // 联动菜单选择
    public function select_linkage($data, $id = 0, $str = '', $default = ' -- ') {

        $string = '<select class="bs-select form-control" '.$str.'>';
        $default && $string.= "<option value='0'>$default</option>";

        if (!IS_DEV) {
            $name = 'tree'.md5(dr_array2string($data).$id.$str.$default);
            $cache = \Phpcmf\Service::L('cache')->get_data($name);
            if ($cache) {
                return $cache;
            }
        }

        $tree = [];
        if (is_array($data)) {
            foreach($data as $t) {
                // 选中操作
                $t['selected'] = $id == $t['id'] ? 'selected' : '';
                $tree[$t['id']] = $t;
            }
        }

        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $string.= $this->icon()->_data($tree)->_linkage_tree_result(0, $str);
        $string.= '</select>';

        unset($this->ret);
        unset($this->data);

        if (!IS_DEV) {
            \Phpcmf\Service::L('cache')->set_data($name, $string, 3600);
        }

        return $string;
    }

    public function ismain($v) {
        $this->ismain = $v;
        return $this;
    }

    /**
     * 栏目选择
     *
     * @param array			$data		栏目数据
     * @param intval/array	$id			被选中的ID
     * @param string		$str		属性
     * @param string		$default	默认选项
     * @param intval		$onlysub	只可选择子栏目
     * @param intval		$is_push	是否验证权限
     * @param intval		$is_first	是否返回第一个可用栏目id
     * @return string
     */
    public function select_category($data, $id = 0, $str = '', $default = ' -- ', $onlysub = 0, $is_push = 0, $is_first = 0) {

        if (\Phpcmf\Service::IS_MOBILE_USER() && strpos($str, 'multiple') !== false) {
            $str = str_replace('style', '_style', $str);
        }

        if (!CI_DEBUG) {
            $name = 'tree'.md5($this->ismain.dr_array2string($data).$id.$str.$default.$onlysub.$is_push.$is_first.\Phpcmf\Service::C()->uid);
            $cache = \Phpcmf\Service::L('cache')->get_data($name);
            if ($cache) {
                return $cache;
            }
        }

        $string = '<select class="bs-select form-control" '.$str.'>'.PHP_EOL;
        $default && $string.= "<option value='0'>$default</option>".PHP_EOL;

        $tree = [];
        $first = 0; // 第一个可用栏目
        $is_cks = 0;
        if (is_array($data)) {
            foreach($data as $t) {
                // 只显示主栏目
                if ($this->ismain && !$t['ismain']) {
                    continue;
                }
                // 用于发布内容时【单页和外链】且为最终栏目时，不显示
                if ($is_push && in_array($t['tid'], [2, 0]) && !$t['child']) {
                    $is_cks = 1;
                    continue;
                }
                // 验证权限
                if (IS_ADMIN && dr_is_app('cqx') && \Phpcmf\Service::M('content', 'cqx')->is_edit($t['id'])) {
                    $is_cks = 1;
                    continue;
                }

                // 栏目发布权限判断,主要筛选栏目下是否有空白选项
                //unset($t['catids'][$t['id']]);
                if ($is_push && $t['child'] == 1 && $t['catids']) {
                    if ($t['is_post']) {
                        $ispost = 1; // 允许发布的父栏目
                    } else {
                        $ispost = 0;
                        foreach ($t['catids'] as $i) {
                            // 当此栏目还存在下级栏目时,逐步判断全部下级栏目是否具备发布权限
                            if (isset($data[$i]) && $data[$i]['child'] == 0) {
                                $ispost = 1; // 可以发布 表示此栏目可用
                                break;
                            }
                        }
                    }
                    if (!$ispost) {
                        // ispost = 0 表示此栏目没有发布权限
                        $is_cks = 1;
                        continue;
                    }
                }
                // 选中操作
                $t['selected'] = (is_array($id) ? dr_in_array($t['id'], $id) : $id == $t['id']) ? 'selected' : '';
                // 是否可选子栏目
                if (isset($t['pcatpost']) && $t['pcatpost']) {
                    $t['html_disabled'] = 0;
                } else {
                    $t['html_disabled'] = $onlysub && $t['child'] ? 1 : 0;
                }
                if (isset($t['setting'])) {
                    unset($t['setting']);
                }
                $tree[$t['id']] = $t;
            }
        }

        $string.= $this->icon()->_data($tree)->_category_tree_result(0, "<option \$selected value='\$id'>\$spacer\$name</option>".PHP_EOL);
        $string.= '</select>'.PHP_EOL;

        if ($is_first) {
            // 第一个子栏目
            $temp = str_replace("disabled value='", '', $string);
            $mark = "value='";
            $first = (int)substr($temp, strpos($temp, $mark) + strlen($mark));
        }

        $data = $is_first ? [$string, $first] : $string;

        unset($this->ret);
        unset($this->data);

        if (!IS_DEV) {
            \Phpcmf\Service::L('cache')->set_data($name, $data, 3600);
        }

        $this->ismain = 0;
        return $data;
    }

    /**
     * 用于栏目选择框
     *
     * @param integer	$myid	要查询的ID
     * @param string	$str	HTML代码方式
     * @param integer	$sid	默认选中
     * @param integer	$adds	前缀
     */
    protected function _category_tree_result($myid, $str, $str2 = '', $sid = 0, $adds = '') {

        if ($this->deep > 5000) {
            return $this->ret; // 防止死循环
        }

        $number = 1;
        $mychild = $this->get_child($myid);

        if (is_array($mychild)) {

            $mytotal = count($mychild);
            foreach ($mychild as $id => $a) {

                $j = $k = '';
                if ($number == $mytotal) {
                    $j.= $this->icon[2];
                } else {
                    $j.= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }

                $spacer = $this->_get_spacer($adds ? $adds.$j : '');
                $selected = $this->_have($sid, $id) ? 'selected' : '';
                $html_disabled = '';
                extract($a);

                //$now = $this->get_child($id);
                // 如果没有子栏目且当前禁用就不再显示
                //if (!$now && $html_disabled) continue;

                if ($html_disabled) {
                    $selected = ' disabled';
                }

                eval("\$this->ret.= \"$str\";");

                $number++;

                // 如果有下级菜单就递归
                if ($a['child']) {
                    $this->_category_tree_result($id, $str, null, $sid, $adds.$k.$this->nbsp);
                }
            }
        }

        return $this->ret;
    }

    /**
     * 得到树型结构
     *
     * @param int ID，表示获得这个ID下的所有子级
     * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '') {

        if ($this->deep > 5000) {
            return $this->ret; // 防止死循环
        }

        $pid = 0;
        $nstr = '';
        $number = 1;
        $mychild = $this->get_child($myid);
        $mytotal = dr_count($mychild);

        if (is_array($mychild)) {
            foreach ($mychild as $id => $value) {
                $j = $k = '';
                if ($number == $mytotal) {
                    $j.= $this->icon[2];
                } else {
                    $j.= $this->icon[1];
                    $k = $adds ? $this->nbsp : '';
                }

                $spacer = $this->_get_spacer($adds ? $adds.$j : '');
                $selected = $id == $sid ? 'selected' : '';
                $class = 'dr_catid_'.$value['id'];
                $parent = defined('SYS_CAT_ZSHOW') && SYS_CAT_ZSHOW ? (!$value['child'] ? '' : '<a href="javascript:void();" class="blue select-cat" childs="'.$value['childids'].'" action="open" catid='.$id.'>[-]</a>&nbsp;') : '';

                extract($value);

                $pid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret.= $nstr;
                $this->get_tree($id, $str, $sid, $adds.$k.$this->nbsp, $str_group);
                $number++;
            }
        }

        return $this->ret;
    }

    /**
     * 得到树型结构
     *
     * @param int ID，表示获得这个ID下的所有子级
     * @return array
     */
    public function get_tree_array($myid, $str = '', $sid = 0, $adds = '', $str_group = '') {

        if ($this->deep > 5000) {
            return $this->result; // 防止死循环
        }

        $mychild = $this->get_child($myid);
        $mytotal = dr_count($mychild);
        $number = 1;

        if (is_array($mychild)) {
            foreach ($mychild as $id => $value) {
                $j = $k = '';
                if ($number == $mytotal) {
                    $j.= $this->icon[2];
                } else {
                    $j.= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                
                $value['spacer'] = $this->_get_spacer($adds ? $adds.$j : '');

                $this->result[$id] = $value;
                $this->get_tree_array($id, $str, $sid, $adds.$k.$this->nbsp, $str_group);
                $number++;
            }
        }
        
        return $this->result;
    }

    /**
     * 同上一方法类似,但允许多选
     */
    public function get_tree_multi($myid, $str, $sid = 0, $adds = '') {

        if ($this->deep > 5000) {
            return $this->ret; // 防止死循环
        }

        $nstr = '';
        $number = 1;
        $mychild = $this->get_child($myid);

        if (is_array($mychild)) {
            $mytotal = count($mychild);
            foreach ($mychild as $id => $a) {

                $j = $k = '';
                if ($number == $mytotal) {
                    $j.= $this->icon[2];
                } else {
                    $j.= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }

                $spacer = $this->_get_spacer($adds ? $adds.$j : '');
                $selected = $this->_have($sid, $id) ? 'selected' : '';

                extract($a);

                eval("\$nstr = \"$str\";");
                $this->ret.= $nstr;
                $this->get_tree_multi($id, $str, $sid, $adds.$k.$this->nbsp);
                $number++;

            }
        }

        return $this->ret;
    }

}