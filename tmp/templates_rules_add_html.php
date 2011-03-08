<script language="JavaScript">
function fCheck() {

	if(rule.name.value =="") {
		alert("请输入采集器名字");
		rule.name.focus();
		return false;
	}

	if(rule.url_I.value =="" && rule.url_II.value =="" && rule.url_III.value =="") {
		alert("请输入索引");
		rule.url_I.focus();
		return false;
	}

	if(rule.pid.value==0) {
		alert("请选择一个分类");
		rule.pid.focus();
		return false;
	}
	return true;
}
</script>

	<center>
	<a name="top">
	<form method=post name="rule" action="index.php?module=addRules&action=submit" onSubmit="return fCheck()">
	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
	<tr>
		<td>
		<br>
		<center>
		<?php
echo $_obj['rules_from'];
?>

		</center>
		<br><br>
		</td>
	</tr>
	<tr>
		<td height="35" bgcolor="#EFEFEF" class="cateborder"><p align="center"><input type="submit" value="添加采集器" class="button">&nbsp;&nbsp;<input type=button value="采集器列表" OnClick="self.location='?module=listRules'" class="button"></p></td>
	</tr>
	</table>
	</form>
	</center>