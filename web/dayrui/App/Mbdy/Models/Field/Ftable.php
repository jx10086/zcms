按需循环写法1：
&lt;?php if ($value) { $key=0; foreach ($value as $v) { ?>
{$ftable}
序号：{$key+1}
&lt;?php  $key++; } } ?>
===========================
按需循环写法2：
{php $mval = $value;}
{loop $mval $v}
{$ftable}
{/loop}
===========================
循环2次的写法：
{php $mval = $arr = array_slice($value, 0, 2);}
{loop $mval $v}
{$ftable}
{/loop}
===========================
表格写法-默认class写法：{dr_get_ftable($fid, $value)}
---------
自定义table的class写法：{dr_get_ftable($fid, $value, 'mytableclass')}
---------
mytableclass就是给表格加class，解析为：table calss="mytableclass"
---------

