<link rel="stylesheet" type="text/css" href="images/js/xtree/css/xtree.css">
<script language="JavaScript" src="images/js/xtree/js/xtree.js"></script>
<script language="JavaScript">
	function show(tdId , td2Id , spanId)
	{
		if(tdId.style.display == 'none')
		{
			tdId.style.display = '';
			td2Id.style.display = '';
			spanId.innerText = '隐藏';
		}
		else
		{
			tdId.style.display = 'none';
			td2Id.style.display = 'none';
			spanId.innerText = '显示';
		}
	}
</script>
								<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
								<tr>
									<td valign="top">
										<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
											<tr>
												<td height="25" width="10%" background="images/tb_background.gif"><center><font color="#FFFFFF">ID</font></center></td>
												<td width="60%" background="images/tb_background.gif">&nbsp;&nbsp;<font color="#FFFFFF">名字</font></td>
												<td width="10%" background="images/tb_background.gif"><center><font color="#FFFFFF">操作</font></center></td>
												<td width="10%" background="images/tb_background.gif"><center><font color="#FFFFFF">连接</font></center></td>
												<td width="10%" background="images/tb_background.gif"><center><font color="#FFFFFF">入库</font></center></td>
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
											<tr>
												<td colspan="4" height="5"></td>
											</tr>
											<tr bgcolor="#F7F7F7">
												<td style="border: 1px solid #CCCCCC" height="30" bgcolor="#EFEFEF"><center><?php
echo $_obj['id'];
?>
</center></td>
												<td style="border: 1px solid #CCCCCC">&nbsp;&nbsp;<a href="?module=listLink&rules=<?php
echo $_obj['id'];
?>
" title="所在分类：<?php
echo $_obj['cateName'];
?>
"><?php
echo $_obj['name'];
?>
</a></td>
												<td style="border: 1px solid #CCCCCC"><center><span id=spanId<?php
echo $_obj['id'];
?>
 onclick="show(nc_<?php
echo $_obj['id'];
?>
,nc2_<?php
echo $_obj['id'];
?>
,spanId<?php
echo $_obj['id'];
?>
)" style="cursor:hand">显示</span></center></td>
												<td style="border: 1px solid #CCCCCC"><center><?php
echo $_obj['link_num'];
?>
</center></td>
												<td style="border: 1px solid #CCCCCC"><center><?php
echo $_obj['import_num'];
?>
</center></td>
											</tr>
											<tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''" id="nc_<?php
echo $_obj['id'];
?>
" style="display:none;">
											<td style="border: 1px solid #CCCCCC" height="25" bgcolor="#EFEFEF" align="center">采集</td>
											<td colspan="4" style="border: 1px solid #CCCCCC" height="25" align="right">&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=testLink&ID=<?php
echo $_obj['id'];
?>
">测试</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=collectionLink&ID=<?php
echo $_obj['id'];
?>
">采集</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=import&rules=<?php
echo $_obj['id'];
?>
">入库</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=editRules&ID=<?php
echo $_obj['id'];
?>
">编辑</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=copyRules&ID=<?php
echo $_obj['id'];
?>
">复制</a>&nbsp;&nbsp;</td>
											</tr>
											<tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''" id="nc2_<?php
echo $_obj['id'];
?>
" style="display:none;">
											<td style="border: 1px solid #CCCCCC" height="25" bgcolor="#EFEFEF" align="center">管理</td>
											<td colspan="4" style="border: 1px solid #CCCCCC" height="25" align="right">
											&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=listLink&rules=<?php
echo $_obj['id'];
?>
">查看</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=updateRulesCount&ID=<?php
echo $_obj['id'];
?>
">统计</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=exportRules&ID=<?php
echo $_obj['id'];
?>
">导出</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=clearRules&ID=<?php
echo $_obj['id'];
?>
">清空</a>&nbsp;&nbsp;<img src="images/p.gif" align="absmiddle">&nbsp;<a href="?module=delRules&ID=<?php
echo $_obj['id'];
?>
" OnClick="return window.confirm('您确定要删除编号为<?php
echo $_obj['id'];
?>
的采集器『<?php
echo $_obj['name'];
?>
』么?');">删除</a>&nbsp;&nbsp;
											</td>
											</tr>
											<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
											<tr>
												<td colspan="4" height="5"></td>
											</tr>
										</table>
									
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
                                  <td align="right"><?php
echo $_obj['pagebar'];
?>
</td>
                                </tr>
                              </table>
                            </td>
									
                            <td width="5" valign="top"></td>
									<td width="35%" valign="top">
									<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
										<tr>
											<td height="25" background="images/tb_background.gif">&nbsp;&nbsp;<font color="#FFFFFF">采集器分类</font></td>
										</tr>
										<tr>
											<td style="border: 1px solid #CCCCCC" height="250" width="10%" bgcolor="#FFFFFF" valign="top">
											<center>
												<table width="95%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
													<tr>
														<td height="5"></td>
													</tr>
													<tr>
														<td ><br><div id="newscategory"></div><br><br>
														</td>
<script language="JavaScript">
		<!--

		var root=new treeItem("采集器分类","?module=rulesList","","",icon.root.src);

		<?php
echo $_obj['tree'];
?>

		<?php
echo $_obj['root'];
?>

		<?php
echo $_obj['item'];
?>


		root.setup(document.getElementById("newscategory"));
		//-->
		</script>
													</tr>
												</table>
											</center>
											</td>
										</tr>
									</table>
									</td>
								</tr>
								</table>