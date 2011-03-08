<?

	function getcontent ($filename)
	{
		if (!file_exists)
		{
			return '文件不存在';
		}
		else
		{
			return file_get_contents ($filename);
		}

	}

	function showform ($action, $title, $description, $contents, $acceptContent = '', $disabled = '')
	{
		echo '	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<title>NEAT Collector 安装程序</title>
	<link href="images/install.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
	<br />
	<br />
	<br />
	<br />
	<br />
	<form action="';
		echo $action;
		echo '" method="post" enctype="multipart/form-data" name="neatinstall">
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="1" class="tableborder">
		<tr>
		<td class="setupbody"><div class="topheader">&nbsp;&nbsp;NEAT Collector 安装向导</div>
			<div class="header_box">
			 ';
		echo '<s';
		echo 'trong>';
		echo $title;
		echo '</strong><br />
			 ';
		echo $description;
		echo '			 </div>
			<div class="firsthr" style="height:1px"><img src="img/pixel.gif" height="1" width="1" alt="" /></div>
			<div class="secondhr" style="height:1px"><img src="img/pixel.gif" height="1" width="1" alt="" /></div>
			<div class="install_box">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10" valign="top"><img src="images/bar.png" alt="" width="148';
		echo '" height="229" /></td>
					<td width="10"><img src="images/pixel.gif" height="1" width="10" alt="" /></td>
					<td valign="top">
				 
					<div style="background-color:#FFFFFF;margin:0px; padding:5px; border:1px inset; width:420px; height:217px; overflow:auto">';
		echo $contents;
		echo '</div>
				 </td>
				</tr>
				</table>
				<br />
				<br />
				<table width="100%" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td width="4%" nowrap="nowrap" class="versioninfo">www.neatstudio.com
	&nbsp;</td>
					<td><div class="firsthr" style="height:1px"><img src="img/pixel.gif" height="1" width="1" alt="" /></div>
						<div class="secondhr" style="height:1px"><img src="im';
		echo 'g/pixel.gif" height="1" width="1" alt="" /></div></td>
				</tr>
				</table>
				<div align="right">';
		echo $acceptContent;
		echo '					<input type="button" class="button" value="&nbsp;上一步&nbsp;" onclick=\'history.go(-1)\'/>&nbsp;<input type="submit" class="button" name=\'nextButton\' value="&nbsp;下一步&nbsp;" ';
		echo $disabled;
		echo '/>
				</div>
			</div></td>
		</tr>
	</table>
	</form>
	</body>
	</html>
';
	}

	function installdb ($sqlStr, $type)
	{
		global $error;
		global $errorInfo;
		if (!$type)
		{
			$sqlStr = str_replace ('DROP', '#DROP', $sqlStr);
		}

		$sqlStr = str_replace ('
', '
', $sqlStr);
		$sqlStr = str_replace ('', '
', $sqlStr);
		$sqlStr = str_replace ('NEAT_', $_POST['tablepre'], $sqlStr);
		$sqlArray = explode (';
', trim ($sqlStr));
		$Que = array ();
		$num = 0;
		unset ($sqlStr);
		foreach ($sqlArray as $query)
		{
			$queries = explode ('
', trim ($query));
			foreach ($queries as $query)
			{
				$Que[$num] .= ($query[0] == '#' ? NULL : $query);
			}

			++$num;
		}

		foreach ($Que as $Qid => $Qsql)
		{
			$Qsql = $Qsql . ';';
			$Qsql = str_replace (';;', ';', $Qsql);
			if (!mysql_query ($Qsql))
			{
				$error = 1;
				$errorInfo .= '<font color="red">建立表发生错误!</font><br><br>问题可能是 <font color=red>' . $_POST['tablepre'] . '</font> 为前缀的表存在.<br>如果你是从原先的程序上进行升级.<br>可以尝试向 NC 开发人员获得数据库升级程序。<BR>';
				return false;
				continue;
			}
		}

	}

	function getphpcfg ($varName, $type = 1)
	{
		if ($type == 2)
		{
			return (get_cfg_var ($varName) ? '<font color=blue>开启</font>' : '<font color=red>关闭</font>');
		}
		else
		{
			return (get_cfg_var ($varName) ? true : false);
		}

	}

	function getphpcfg2 ($varName, $type = 1)
	{
		if ($type == 2)
		{
			return (get_cfg_var ($varName) ? '<font color=red>开启</font>' : '<font color=blue>关闭</font>');
		}
		else
		{
			return (get_cfg_var ($varName) ? true : false);
		}

	}

	function getfun ($funName, $type = 1)
	{
		if ($type == 2)
		{
			return (false !== function_exists ($funName) ? '<font color=blue>开启</font>' : '<font color=red>关闭</font>');
		}
		else
		{
			return (false !== function_exists ($funName) ? true : false);
		}

	}

	function getwriteable ($check)
	{
		return (!$check ? '<font color=blue>可写</blue>' : '<font color=red>不可写</font>');
	}

?>
