选择值：
&lt;?php if ($value) { foreach ($value as $v) { ?>
{$v}
&lt;?php } } ?>

===========================
选择的名称：
{php $field = dr_field_options($fid);}
{loop $field $v $name}
{if in_array($v, $value)}
{$name}
{/if}
{/loop}
