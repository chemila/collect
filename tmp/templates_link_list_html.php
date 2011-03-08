<script>
function CheckAll()
{
	for (var i=0;i<delform.elements.length;i++)
	{
		var e = delform.elements[i];
		if (e.type=='checkbox')
		{
			if (e.checked == false)
				e.checked = true;
			else
				e.checked = false;
		}
	}
}

function changeFormAction(formname,actionvalue)
{
	formname.action = "index.php?module="+ actionvalue ;
}
//2005-4-1 gouki
//说明:本来想用settimeout来设置延时,结果老是失败,就用alert了!
//changeFormAction可以不用!
//可以在showFormAction中再加一个参数,如showFormAction(formname, value)
//javascript 的函数功能和PHP不一样,可以少掉一个参数的说~~~即使是两个参数,你写 一个,也不会报错的!
//但只能依次少，比如三个参数，你可以少最后一个参数，但不能少第二个参数！ 
/*
	function showFormAction(formname , actionvalue)
	{
		if(formname.action == "")
		{
			formname.action = "index.php?module=delLink";
		}
		else
		{
			formname.action = "index.php?modele=" + actionvalue ;
		}
		formname.submit();
		alert('删除链接成功');
	//	window.settimeout("1000");
		location.reload();
	
	}
*/

function showFormAction(formname)
{
	if(formname.action == "")
	{
		formname.action = "index.php?module=delLink";
	}
	formname.submit();
	alert('删除链接成功');
//	window.settimeout("1000");
	location.reload();
}
function setForm(formname , status)
{
	if(formname.action == "")
	{
		formname.action = "index.php?module=adopt&adopt=" + status;
	}
	formname.submit();
	alert('更新采用状态成功');
//	window.settimeout("1000");
//	location.reload();
}

function setFormRules(formname ,rulesid , status)
{
	if(formname.action == "")
	{
		formname.action = "index.php?module=adopt&rules="+ rulesid+"&adopt=" + status;
	}
	formname.submit();
//	window.settimeout("1000");
//	location.reload();
}

//action="index.php?module=delLink"
</script>
			<center>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
							<tr>
								<td height="25" colspan="7" align="right">
								<a href="?module=import&rules=<?php
echo $_obj['rulesID'];
?>
">把连接入库</a> | <a href="?module=listImport&rules=<?php
echo $_obj['rulesID'];
?>
">文章库列表</a> | <a href="?module=listRules">采集器列表</a>
								</td>
							</tr>
							<tr>
								<td background="images/tb_background.gif" height="30" width="10%"><center><font color="#FFFFFF">ID</font></center></td>
								<td background="images/tb_background.gif" width="40%">&nbsp;&nbsp;<font color="#FFFFFF">名字</font></td>
								<td background="images/tb_background.gif" width="20%"><center><font color="#FFFFFF">采集器</font></center></td>
								<td background="images/tb_background.gif" width="10%"><center><font color="#FFFFFF">时间</font></center></td>
								<td background="images/tb_background.gif" width="5%"><center><font color="#FFFFFF">采用</font></center></td>
								<td background="images/tb_background.gif" width="5%"><center><font color="#FFFFFF">入库</font></center></td>
								<td background="images/tb_background.gif" width="10%"><center><font color="#FFFFFF">管理</font></center></td>
							</tr>
							<tr>
								<td height="30" bgcolor="#EFEFEF" style="border: 1px solid #CCCCCC"><center>采集器</center></td>
								<td bgcolor="#EFEFEF" style="border: 1px solid #CCCCCC">&nbsp;
								<select name="rulesID" onchange="location='?module=listLink&rules='+this.options[this.selectedIndex].value">
								<option>全部采集器</option>
								<?php
if (!empty($_obj['option'])){
if (!is_array($_obj['option']))
$_obj['option']=array(array('option'=>$_obj['option']));
$_tmp_arr_keys=array_keys($_obj['option']);
if ($_tmp_arr_keys[0]!='0')
$_obj['option']=array(0=>$_obj['option']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['option'] as $rowcnt=>$option) {
$option['ROWCNT']=$rowcnt;
$option['ALTROW']=$rowcnt%2;
$option['ROWBIT']=$rowcnt%2;
$_obj=&$option;
?>
								<option value="<?php
echo $_obj['rulesID'];
?>
"<?php
echo $_obj['rulesSelected'];
?>
><?php
echo $_obj['rulesName'];
?>
</option>
								<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
								</select></td>
								<td height="30" align="right" colspan="5" bgcolor="#EFEFEF" style="border: 1px solid #CCCCCC"><?php
echo $_obj['pageBar'];
?>
</td>
							</tr>
							<?php
if (!empty($_obj['list'])){
if (!is_array($_obj['list']))
$_obj['list']=array(array('list'=>$_obj['list']));
$_tmp_arr_keys=array_keys($_obj['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['list']=array(0=>$_obj['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['list'] as $rowcnt=>$list) {
$list['ROWCNT']=$rowcnt;
$list['ALTROW']=$rowcnt%2;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
							<form method=post name="delform" >
							<tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''">
								<td style="border: 1px solid #CCCCCC" height="25" bgcolor="#F7F7F7"><center><?php
echo $_obj['id'];
?>
</center></td>
								<td style="border: 1px solid #CCCCCC"><input type="checkbox" name="ID[]" value="<?php
echo $_obj['id'];
?>
">&nbsp;<a href="<?php
echo $_obj['url'];
?>
" target="_blank" title="标题:<?php
echo $_obj['fullTitle'];
?>
<br>地址:<?php
echo $_obj['url'];
?>
"><?php
echo $_obj['title'];
?>
</a></td>
								<td style="border: 1px solid #CCCCCC"><center><?php
echo $_obj['rules'];
?>
</center></td>
								<td style="border: 1px solid #CCCCCC"><center><?php
echo $_obj['date'];
?>
</center></td>
								<td style="border: 1px solid #CCCCCC" bgcolor="<?php
echo $_obj['adopt_bgcolor'];
?>
"><center><a href="?module=adopt&adopt=<?php
echo $_obj['adopt_change'];
?>
&ID=<?php
echo $_obj['id'];
?>
"><?php
echo $_obj['adopt_str'];
?>
</a></center></td>
								<td style="border: 1px solid #CCCCCC" bgcolor="<?php
echo $_obj['import_bgcolor'];
?>
"><center><?php
echo $_obj['import_str'];
?>
</center></td>
								<td style="border: 1px solid #CCCCCC"><center><a href="?module=delLink&ID=<?php
echo $_obj['id'];
?>
" OnClick="return window.confirm('您确定要删除编号为<?php
echo $_obj['id'];
?>
的连接『<?php
echo $_obj['title_js'];
?>
』么?');">删除</a></center></td>
							</tr>
							<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
							<tr>
								<td height="25" colspan="6" bgcolor="#EFEFEF" style="border: 1px solid #CCCCCC">
								&nbsp&nbsp;总数 : <?php
echo $_obj['total'];
?>
 条 | 采用 : <?php
echo $_obj['adopt'];
?>
 条 | 禁用 : <?php
echo $_obj['unadopt'];
?>
 条 | 已经入库 : <?php
echo $_obj['import'];
?>
 条 | 暂未入库 : <?php
echo $_obj['unimport'];
?>
 条
								</td>
								<td bgcolor="#F7F7F7" style="border: 1px solid #CCCCCC"><center><a href="#top">返回顶部</a></center></td>
							</tr>
							<tr>
								<td height="25" align="right" colspan="7">&nbsp;&nbsp;&nbsp;<a href="#selectall" alt="<BR>把该页面显示的连接全部选中<BR><BR>" onclick="CheckAll()">全选连接</a>&nbsp;&nbsp;&nbsp;<a href="#selectall" alt="<BR>将该采集器所有连接采用设为<font color=red>是</font><BR><BR>" onclick="setFormRules(delform , '<?php
echo $_obj['rulesID'];
?>
' , 'yes')">将该采集器所有连接采用设为<font color=red>是</font></a>&nbsp;&nbsp;&nbsp;<a href="#selectall" alt="<BR>将该采集器所有连接采用设为<font color=red>否</font><BR><BR>" onclick="setFormRules(delform , '<?php
echo $_obj['rulesID'];
?>
' ,'no')">将该采集器所有连接采用设为<font color=red>否</font></a>&nbsp;&nbsp;&nbsp;<a href="#" alt="<BR>选中连接采用设为<font color=red>是</font><BR><BR>" onclick="setForm(delform , 'yes');">选中连接采用设为<font color=red>是</font></a>&nbsp;&nbsp;&nbsp;<a href="#delall" alt="<BR>选中连接采用设为<font color=red>否</font><BR><BR>" onclick="setForm(delform , 'no');">选中连接采用设为<font color=red>否</font></a>&nbsp;&nbsp;<a OnClick="if( window.confirm('您确定要删除您选中的连接么?') ){showFormAction(delform);}" href="javascript:" alt="<BR>把该页面选择了的连接全部删除<BR><BR>">删除选择</a></td>
							</tr>
						</table>
						</center>
						</form>
