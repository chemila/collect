<script type="text/javascript">
<!--
currObj = "uuuu";
function getActiveText(obj)
{
	currObj = obj;
}

function addTag(code)
{
	addText(code);
}

function addText(ibTag)
{
	var isClose = false;
	var obj_ta = currObj;

	if (obj_ta.isTextEdit)
	{
		obj_ta.focus();
		var sel = document.selection;
		var rng = sel.createRange();
		rng.colapse;

		if((sel.type == "Text" || sel.type == "None") && rng != null)
		{
			rng.text = ibTag;
		}

		obj_ta.focus();

		return isClose;
	}
	else
		return false;
}	
-->
</script>
[ <a href="?module=listRules">返回采集列表</a> | <a href="#index_pos">连接索引设置</a> | <a href="#http">HTTP选项</a> | <a href="#link_area_pos">连接区域规则</a> | <a href="#art_area_pos">文章区域规则</a> | <a href="#page_area_pos">分页区域规则</a> | <a href="#filter_area_pos">过滤区域设置</a> ]
<br><br>
		<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">采集器命名</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>采集器名字</center></td>
						<td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;<input type="text" name="name" value="<?php
echo $_obj['name'];
?>
" size="50" class="button"></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table><br>
				<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">分类</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>分类</center></td>
						<td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;<select name="pid"><?php
echo $_obj['option'];
?>
</select></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table><br>
			<a name="index_pos">
			<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">连接索引设置</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="30"><center><input type="radio" name="indexType" value="1" <?php
echo $_obj['indexType_I'];
?>
>&nbsp;从一个网页</center></td>
						<td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;<input type="text" name="url_I" value="<?php
echo $_obj['url_I'];
?>
" size="50" class="button"></td>
					</tr>
					<tr>
						<td bgcolor="#F7F7F7" class="pxborder"><center><input type="radio" name="indexType" value="2" <?php
echo $_obj['indexType_II'];
?>
>&nbsp;指定多网页</center></td>
						<td bgcolor="#EFEFEF" class="pxborder"><br>
						&nbsp;&nbsp;<textarea name="url_II" rows="5" cols="70"><?php
echo $_obj['url_II'];
?>
</textarea><br><br>
						&nbsp;&nbsp;在上面的输入框中输入指定的网页连接,一行一个地址<br><br>
						</td>
					</tr>
					<tr>
						<td bgcolor="#F7F7F7" class="pxborder"><center><input type="radio" name="indexType" value="3" <?php
echo $_obj['indexType_III'];
?>
>&nbsp;指定范围内</center></td>
						<td bgcolor="#EFEFEF" class="pxborder"><br>
						&nbsp;&nbsp;<input type="text" name="url_III" value="<?php
echo $_obj['url_III'];
?>
" size="80" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="url_III" class="button"><br><br>
						&nbsp;&nbsp;<input type="text" name="page_start" size="3" value="<?php
echo $_obj['page_start'];
?>
" class="button">&nbsp;到&nbsp;<input type="text" name="page_end" size="3" class="button" value="<?php
echo $_obj['page_end'];
?>
">&nbsp;的页面范围内.&nbsp;&nbsp;先乘&nbsp;<input type="text" name="page_mula" size="3" value="<?php
echo $_obj['page_mula'];
?>
" class="button">&nbsp;后加&nbsp;<input type="text" name="page_add" size="3" value="<?php
echo $_obj['page_rules'];
?>
" class="button">&nbsp;在补位&nbsp;<input type="text" name="page_fill" size="3" class="button" value="<?php
echo $_obj['page_rules'];
?>
">&nbsp;&nbsp;可用标签：<font onClick="addTag('[分页]')" style="CURSOR: hand" alt="插入[分页]标签"><b>[分页]</b></font><br><br>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			<br>
			<a name="http">
			<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">HTTP选项</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>获取方式</center></td>
						<td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;<input type="radio" name="method" value="1" <?php
echo $_obj['methodType_GET'];
?>
>GET <input type="radio" name="method" value="2" <?php
echo $_obj['methodType_POST'];
?>
>POST</td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>缩减优化</center></td>
					  <td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;
					    <input name="delr" type="checkbox" id="delr" value="1" <?php
echo $_obj['replaceRNTType_R'];
?>
>
					    过滤空格&nbsp;
					    <input name="deln" type="checkbox" id="deln" value="1" <?php
echo $_obj['replaceRNTType_N'];
?>
>
					    过滤回车&nbsp;
				      <input name="delt" type="checkbox" id="delt" value="1" <?php
echo $_obj['replaceRNTType_T'];
?>
>
				      过滤TAB&nbsp;
					  <input name="debugshow" type="checkbox" id="debugshow" value="1" <?php
echo $_obj['replaceRNTType_D'];
?>
>
				      显示调试
				      <input name="charset" type="checkbox" id="debugshow" value="1" <?php
echo $_obj['replaceRNTType_C'];
?>
>
				      utf8</td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>POST内容</center></td>
						<td bgcolor="#EFEFEF" class="pxborder"><br>&nbsp;&nbsp;输入区域：<span onClick="document.rule.posts.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.posts.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>&nbsp;&nbsp;可用标签：<font onClick="addTag('[分页]')" style="CURSOR: hand" alt="插入[分页]标签"><b>[分页]</b></font><br><br>&nbsp;&nbsp;<textarea name="posts" rows="5" cols="80" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="posts"><?php
echo $_obj['posts'];
?>
</textarea><br><br></td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>Cookie内容</center></td>
						<td bgcolor="#EFEFEF" class="pxborder"><br>&nbsp;&nbsp;输入区域：<span onClick="document.rule.cookies.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.cookies.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span><br><br>&nbsp;&nbsp;<textarea name="cookies" rows="5" cols="80" id="cookies"><?php
echo $_obj['cookies'];
?>
</textarea><br><br></td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>
						  Referer
						</center></td>
					  <td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;						<input type="text" name="referer" id="referer" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" size="60" value="<?php
echo $_obj['referer'];
?>
" class="button">
&nbsp;&nbsp;快捷：<font onClick="addTag('<?php
echo $_obj['url_I'];
?>
')" style="CURSOR: hand"><b>域名</b></font>&nbsp;<font onClick="addTag('http://www.baidu.com/')" style="CURSOR: hand"><b>百度</b></font>&nbsp;<font onClick="addTag('http://www.google.com/')" style="CURSOR: hand"><b>狗狗</b></font> </td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#F7F7F7" class="pxborder" height="35"><center>
						  User-Agent
						</center></td>
					  <td bgcolor="#EFEFEF" class="pxborder">&nbsp;&nbsp;						<input type="text" name="useragent" id="referer" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" size="60" value="<?php
echo $_obj['useragent'];
?>
" class="button">
&nbsp;&nbsp;快捷：<font onClick="addTag('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Alexa Toolbar)')" style="CURSOR: hand"><b>普通</b></font>&nbsp;<font  onClick="addTag('Baiduspider')" style="CURSOR: hand"><b>百度</b></font>&nbsp;<font onClick="addTag('googlebot')" style="CURSOR: hand"><b>狗狗</b></font> </td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table><br>
			<br>
			<a name="link_area_pos">
			<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">连接区域规则</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
						<tr> 
						  <td bgcolor="#F7F7F7" width="20%" class="pxborder"> 
							<center>
							  连接 
							</center>
						  </td>
						  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
							<input type="checkbox" name="multi_link" value="1" <?php
echo $_obj['multi_link'];
?>
>
							&nbsp;多行匹配&nbsp; 
							<input type="checkbox" name="enter_link" value="1" <?php
echo $_obj['enter_link'];
?>
>
							&nbsp;UNIX格式&nbsp;
							&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_link.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_link.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
							&nbsp;&nbsp;可用标签：<font onClick="addTag('[连接]')" style="CURSOR: hand"><b>[连接]</b></font>&nbsp;<font  onClick="addTag('[标题]')" style="CURSOR: hand"><b>[标题]</b></font>&nbsp;<font  onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
							<br><br>
							&nbsp;&nbsp; 
							<textarea name="area_link" rows="9" cols="80" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_link"><?php
echo $_obj['area_link'];
?>
</textarea><br><br>
						  </td>
					</tr>
						<tr> 
						  <td bgcolor="#F7F7F7" width="20%" class="pxborder"> 
							<center>
							  替换 
							</center>
						  </td>
						  <td bgcolor="#EFEFEF" class="pxborder" height="30">&nbsp;&nbsp; 
							<input type="text" name="link_replace" id="link_replace" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" size="60" value="<?php
echo $_obj['link_replace'];
?>
" class="button">
							&nbsp;&nbsp;可用标签：<font onClick="addTag('[文章编号]')" style="CURSOR: hand"><b>[文章编号]</b></font>
						  </td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<a name="art_area_pos">
		<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">文章区域规则</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
				  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
					<tr>
					  <td bgcolor="#F7F7F7" width="20%" class="pxborder"> 
						<center>
						  标题 
						</center>
					  </td>
					  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
						<input type="checkbox" name="multi_title" value="1" <?php
echo $_obj['multi_title'];
?>
>
						&nbsp;多行匹配&nbsp; 
						<input type="checkbox" name="enter_title" value="1" <?php
echo $_obj['enter_title'];
?>
>
						&nbsp;UNIX格式&nbsp;
						&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_title.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_title.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
						&nbsp;&nbsp;可用标签：<font onClick="addTag('[标题]')" style="CURSOR: hand"><b>[标题]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
						<br><br>
						&nbsp;&nbsp; 
						<textarea name="area_title" rows="9" cols="80" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_title"><?php
echo $_obj['area_title'];
?>
</textarea><br><br>
					  </td>
					</tr>
					<tr> 
					  <td bgcolor="#F7F7F7" class="pxborder"> 
						<center>
						  内容 
						</center>
					  </td>
					  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
						<input type="checkbox" name="multi_body" value="1" <?php
echo $_obj['multi_body'];
?>
>
						&nbsp;多行匹配&nbsp; 
						<input type="checkbox" name="enter_body" value="1" <?php
echo $_obj['enter_body'];
?>
>
						&nbsp;UNIX格式&nbsp;
						&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_body.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_body.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
						&nbsp;&nbsp;可用标签：<font onClick="addTag('[内容]')" style="CURSOR: hand"><b>[内容]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
						<br><br>
						&nbsp;&nbsp; 
						<textarea name="area_body" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_body"><?php
echo $_obj['area_body'];
?>
</textarea><br><br>
					  </td>
					</tr>
<tr> 
					  <td bgcolor="#F7F7F7" class="pxborder"> 
						<center>
						  作者 
						</center>
					  </td>
					  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
						<input type="checkbox" name="multi_author" value="1" <?php
echo $_obj['multi_author'];
?>
>
						&nbsp;多行匹配&nbsp; 
						<input type="checkbox" name="enter_author" value="1" <?php
echo $_obj['enter_author'];
?>
>
						&nbsp;UNIX格式&nbsp;
						&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_author.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_author.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
						&nbsp;&nbsp;可用标签：<font onClick="addTag('[作者]')" style="CURSOR: hand"><b>[作者]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
						<br><br>
						&nbsp;&nbsp; 
						<textarea name="area_author" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_author"><?php
echo $_obj['area_author'];
?>
</textarea><br><br>
					  </td>
					</tr>
<tr> 
					  <td bgcolor="#F7F7F7" class="pxborder"> 
						<center>
						  简介 
						</center>
					  </td>
					  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
						<input type="checkbox" name="multi_intro" value="1" <?php
echo $_obj['multi_intro'];
?>
>
						&nbsp;多行匹配&nbsp; 
						<input type="checkbox" name="enter_intro" value="1" <?php
echo $_obj['enter_intro'];
?>
>
						&nbsp;UNIX格式&nbsp;
						&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_intro.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_intro.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
						&nbsp;&nbsp;可用标签：<font onClick="addTag('[简介]')" style="CURSOR: hand"><b>[简介]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
						<br><br>
						&nbsp;&nbsp; 
						<textarea name="area_intro" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_intro"><?php
echo $_obj['area_intro'];
?>
</textarea><br><br>
					  </td>
					</tr>
<tr> 
					  <td bgcolor="#F7F7F7" class="pxborder"> 
						<center>
						  来源 
						</center>
					  </td>
					  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
						<input type="checkbox" name="multi_from" value="1" <?php
echo $_obj['multi_from'];
?>
>
						&nbsp;多行匹配&nbsp; 
						<input type="checkbox" name="enter_from" value="1" <?php
echo $_obj['enter_from'];
?>
>
						&nbsp;UNIX格式&nbsp;
						&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_from.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_from.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
						&nbsp;&nbsp;可用标签：<font onClick="addTag('[来源]')" style="CURSOR: hand"><b>[来源]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
						<br><br>
						&nbsp;&nbsp; 
						<textarea name="area_from" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_from"><?php
echo $_obj['area_from'];
?>
</textarea><br><br>
					  </td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<a name="page_area_pos">
		<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">分页区域规则</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
				  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
						<tr> 
						  <td bgcolor="#F7F7F7" class="pxborder"> 
							<center>
							  内容分页类型
							</center>
						  </td>
						  <td bgcolor="#EFEFEF" height="30" class="pxborder">
							&nbsp;&nbsp;&nbsp;<input type="radio" name="body_page_type" value="0" onclick="nextlink('hide')" <?php
echo $_obj['body_page_type_all'];
?>
>
							全部列出形式导航 
							<input type="radio" name="body_page_type" value="1" onclick="nextlink('show')" <?php
echo $_obj['body_page_type_next'];
?>
>
							上下页形式导航&nbsp; </td>
						</tr>
						<tr> 
						  <td bgcolor="#F7F7F7" width="20%" class="pxborder"> 
							<center>
							  内容分页区域
							</center>
						  </td>
						  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
							<input type="checkbox" name="multi_body_page" value="1" <?php
echo $_obj['multi_body_page'];
?>
>
							&nbsp;多行匹配&nbsp; 
							<input type="checkbox" name="enter_body_page" value="1" <?php
echo $_obj['enter_body_page'];
?>
>
							&nbsp;UNIX格式&nbsp;
							&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_body_page.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_body_page.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
							&nbsp;&nbsp;可用标签：<font onClick="addTag('[分页区域]')" style="CURSOR: hand"><b>[分页区域]</b></font>&nbsp;<font 	onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
							<br><br>
							&nbsp;&nbsp; 
							<textarea name="area_body_page" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_body_page"><?php
echo $_obj['area_body_page'];
?>
</textarea><br><br>
						  </td>
						</tr>
						<tr id='next_link' style="display:<?php
echo $_obj['next_link_display'];
?>
"> 
						  <td bgcolor="#F7F7F7" class="pxborder"> 
							<center>"下一页"的连接</center>
						  </td>
						  <td bgcolor="#EFEFEF" class="pxborder"><br> &nbsp;&nbsp; 
							<input type="checkbox" name="multi_body_page_link" value="1" <?php
echo $_obj['multi_body_page_link'];
?>
>
							&nbsp;多行匹配&nbsp; 
							<input type="checkbox" name="enter_body_page_link" value="1" <?php
echo $_obj['enter_body_page_link'];
?>
>
							&nbsp;UNIX格式&nbsp;
							&nbsp;&nbsp;输入区域：<span onClick="document.rule.area_body_page_link.rows-=2" style='font-size:9pt; color:#666666; cursor:hand'>缩小</span> <span onClick="document.rule.area_body_page_link.rows+=2" style='font-size:9pt; color:#666666C; cursor:hand'>扩大</span>
							&nbsp;&nbsp;可用标签：<font onClick="addTag('[连接]')" style="CURSOR: hand"><b>[连接]</b></font>&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
							<br><br>
							&nbsp;&nbsp; 
							<textarea name="area_body_page_link" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="area_body_page_link"><?php
echo $_obj['area_body_page_link'];
?>
</textarea><br><br>
						  </td>
						</tr>
						<tr>
							<td colspan="2"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<a name="filter_area_pos">
		<table bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
			<tr>
				<td width="80%" height="25" background="images/tb_background.gif">&nbsp;&nbsp;<img src="images/icon.gif"  align="absmiddle">&nbsp;&nbsp;<font color="#FFFFFF">过滤区域规则</font></td>
				<td width="20%" background="images/tb_background.gif"><a href="#top"><center><font color="#FFFFFF">↑返回顶部</font></center></a></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#F4F4F2">
				  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
						<tr> 
						  <script language="javascript">
							function setid()
							{
								  str='<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">';
								  if(!window.rule.filters.value)
									  window.rule.filters.value=1;
								  for(i=0;i<(eval(window.rule.filters.value));i++)
									 str+='<tr><td><br>&nbsp;&nbsp; 过滤器名称:&nbsp;&nbsp;<input type="text" name="add_filter_name[]" value="" class="button">&nbsp;&nbsp;<input type="checkbox" name="add_filter_multi[]" value="1" checked>&nbsp;多行匹配&nbsp; <input type="checkbox" name="add_filter_enter[]" value="1">&nbsp;UNIX格式&nbsp;&nbsp;可用标签：&nbsp;<font onClick="addTag(\'[变数]\')" style="CURSOR: hand"><b>[变数]</b></font><br><br>&nbsp;&nbsp; <textarea name="add_filter_rule[]" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)"></textarea><BR><BR></td></tr>';

								  window.filterid.innerHTML=str+'</table><br>';
							}
						</script>
						<script>
						function showObj(objID, action)
						{
							var obj = document.getElementById(objID);
							action = (action == "show") ? "block" : "none";
							obj.style.display = action;
						}

						function nextlink(action)
						{
							showObj('next_link', action);
						}
						</script>
						  <td bgcolor="#F7F7F7" width="20%" class="pxborder"> 
							<center>
							  过滤
							</center>
						  </td>
						  <td bgcolor="#EFEFEF" class="pxborder"><br>&nbsp;&nbsp; 过滤器数目&nbsp;&nbsp; 
							<input type="text" name="filters" id="filters" value="1" size="3" class="button">
							&nbsp;&nbsp; 
							<input type="button" onclick="setid();" value="设定" class="button">
							&nbsp;&nbsp;<hr color="#CCCCCC" size="1">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
							  <tr> 
								<td> 
								  <?php
if (!empty($_obj['filter'])){
if (!is_array($_obj['filter']))
$_obj['filter']=array(array('filter'=>$_obj['filter']));
$_tmp_arr_keys=array_keys($_obj['filter']);
if ($_tmp_arr_keys[0]!='0')
$_obj['filter']=array(0=>$_obj['filter']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['filter'] as $rowcnt=>$filter) {
$filter['ROWCNT']=$rowcnt;
$filter['ALTROW']=$rowcnt%2;
$filter['ROWBIT']=$rowcnt%2;
$_obj=&$filter;
?>
								  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
									<tr> 
									  <td><br> &nbsp;&nbsp; 过滤器名称:&nbsp;&nbsp;<input type="text" name="filter_name[<?php
echo $_obj['id'];
?>
]" value="<?php
echo $_obj['filter_name'];
?>
" class="button">&nbsp;&nbsp;
										<input type="checkbox" name="filter_multi[<?php
echo $_obj['id'];
?>
]" value="1" <?php
echo $_obj['filter_multi'];
?>
>
										&nbsp;多行匹配&nbsp; 
										<input type="checkbox" name="filter_enter[<?php
echo $_obj['id'];
?>
]" value="1" <?php
echo $_obj['filter_enter'];
?>
>
										&nbsp;UNIX格式
										&nbsp;&nbsp;可用标签：&nbsp;<font onClick="addTag('[变数]')" style="CURSOR: hand"><b>[变数]</b></font>
										<input type="checkbox" name="filter_del[<?php
echo $_obj['id'];
?>
]" value="1">
										&nbsp;删除<br><br>
										&nbsp;&nbsp; 
										<textarea name="filter_rule[<?php
echo $_obj['id'];
?>
]" rows="9" cols="80" onclick="getActiveText(this)"  onchange="getActiveText(this)" id="filter_rule[<?php
echo $_obj['id'];
?>
]"><?php
echo $_obj['filter_rule'];
?>
</textarea><br><br>
									  </td>
									</tr>
								  </table>
								  <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
								</td>
							  </tr>
							  <tr> 
								<td id="filterid">&nbsp;</td>
							  </tr>
						</table>
					  </td>
					</tr>
				  </table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			</table>