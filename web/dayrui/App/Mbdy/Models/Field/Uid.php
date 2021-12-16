<br>会员的id号：{$value}
<br>会员头像：{dr_avatar($value)}<br><br>
<font color="red">如果调用会员其他字段，必须先调用这句话：<br>{php $user=dr_member_info($value);}</font>
<br>
<br>会员name：{$user.name}
<br>会员username：{$user.username}
<br>会员phone：{$user.phone}
<br>会员email：{$user.email}
<br><a href="{SELF}?s=mbdy&c=member&return=user">单击生成其他会员自定义字段</a>