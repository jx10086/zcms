{if $value}
{module module=$module IN_id=$value return=r}
{$r.url}
{$r.title}
......
{/module}
{else}
没有关联
{/if}
------------------
数据个数：{php echo $value ? substr_count(tirm($value, ','), ',')+1 : 0;}