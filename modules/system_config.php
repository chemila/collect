<?

	if (!$_GET['action'])
	{
		$regTypeList[2] = '个人版';
		$regTypeList[3] = '组织版';
		$regTypeList[4] = '企业版';
		$i = 0;
		foreach ($regTypeList as $k => $v)
		{
			(REG_TYPE == $k ? $regtype_selected = 'selected' : $regtype_selected = '');
			$regtype_option[$i]['reg_type_value'] = $k;
			$regtype_option[$i]['reg_type_selected'] = $regtype_selected;
			$regtype_option[$i]['reg_type_name'] = $v;
			++$i;
		}

		(DEBUG == 1 ? $debug_open = 'selected' : $debug_close = 'selected');
		$debug_option[0]['debug_value'] = '0';
		$debug_option[0]['debug_selected'] = $debug_close;
		$debug_option[0]['debug_name'] = '关闭';
		$debug_option[1]['debug_value'] = '1';
		$debug_option[1]['debug_selected'] = $debug_open;
		$debug_option[1]['debug_name'] = '开启';
		$tp->set_templatefile ('templates/sys_config.html');
		$tp->assign ('username', NEAT_USERNAME);
		$tp->assign ('password', NEAT_PASSWORD);
		$tp->assign ('reg_name', REG_NAME);
		$tp->assign ('reg_type_option', $regtype_option);
		$tp->assign ('reg_server_sn', REG_SERVER_SN);
		$tp->assign ('reg_local_sn', REG_LOCAL_SN);
		$tp->assign ('page_rules', NUM_RULES_ONEPAGE);
		$tp->assign ('page_link', NUM_LINK_ONEPAGE);
		$tp->assign ('page_import', NUM_IMPORT_ONEPAGE);
		$tp->assign ('gd_font', GD_FONT);
		$tp->assign ('debug_option', $debug_option);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '系统参数设置';
	}
	else
	{
		if (!$_POST['username'])
		{
			error ('为了您系统的安全考虑,登陆名字不能为空');
		}

		if (!$_POST['password'])
		{
			error ('为了您系统的安全考虑,登陆密码不能为空');
		}

		if (!$_POST['page_rules'])
		{
			error ('采集器配置列表分页不能为空');
		}

		if (!$_POST['page_link'])
		{
			error ('连接列表分页不能为空');
		}

		if (!$_POST['page_import'])
		{
			error ('文章列表分页不能为空');
		}

		if (!is_numeric ($_POST['page_rules']))
		{
			error ('采集器配置列表分页参数必须是数字');
		}

		if (!is_numeric ($_POST['page_link']))
		{
			error ('连接列表分页参数必须是数字');
		}

		if (!is_numeric ($_POST['page_import']))
		{
			error ('文章列表分页参数必须是数字');
		}

		foreach ($configIgnoreExt as $k => $v)
		{
			$ignoreExtArray .= '\'' . $v . '\',';
		}

		$configContents = '<?php

// mysql\'s setting

define(\'DB_SERVER\', \'' . DB_SERVER . '\');
' . 'define(\'DB_USER\', \'' . DB_USER . '\');
' . 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');
' . 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');
' . 'define(\'DB_TB_PRE\', \'' . DB_TB_PRE . '\');

' . '// GD font

' . 'define(\'GD_FONT\', \'' . $_POST['gd_font'] . '\');

' . '// error

' . 'define(\'DEBUG\', ' . $_POST['debug'] . ');

' . '// table setting

' . 'define(\'TB_RULES\', DB_TB_PRE . \'rules\');
' . 'define(\'TB_LINKS\', DB_TB_PRE . \'links\');
' . 'define(\'TB_DATA\', DB_TB_PRE . \'datas\');
' . 'define(\'TB_DB2DB\', DB_TB_PRE . \'export\');
' . 'define(\'TB_CATE\', DB_TB_PRE . \'category\');
' . 'define(\'TB_FILTER\', DB_TB_PRE . \'filter\');

' . '// cookie info

' . 'define(\'COOKIE_PREFIX\', \'' . COOKIE_PREFIX . '\');
' . 'define(\'COOKIE_DOMAIN\', \'' . COOKIE_DOMAIN . '\');
' . 'define(\'COOKIE_PATH\', \'' . COOKIE_PATH . '\');

' . '// num of onepage

' . 'define(\'NUM_RULES_ONEPAGE\', ' . $_POST['page_rules'] . ');
' . 'define(\'NUM_LINK_ONEPAGE\', ' . $_POST['page_link'] . ');
' . 'define(\'NUM_IMPORT_ONEPAGE\', ' . $_POST['page_import'] . ');

' . '// registration information

' . 'define(\'REG_NAME\', \'' . $_POST['reg_name'] . '\');
' . 'define(\'REG_TYPE\', ' . $_POST['reg_type'] . ');
' . 'define(\'REG_SERVER_SN\', \'' . $_POST['reg_server_sn'] . '\');
' . 'define(\'REG_LOCAL_SN\', \'' . $_POST['reg_local_sn'] . '\');

' . '// username password

' . 'define(\'NEAT_USERNAME\', \'' . $_POST['username'] . '\');
' . 'define(\'NEAT_PASSWORD\', \'' . $_POST['password'] . '\');

' . '$configIgnoreExt = array(' . $ignoreExtArray . ');

' . '?>';
		$fp = fopen ('includes/config.inc.php', 'w+');
		fwrite ($fp, $configContents);
		fclose ($fp);
		$url = '?module=sysConfig';
		showloading ($url, '配置参数编辑成功', '配置文件已经更新,现在返回配置参数页.', 1);
		$tpShowBody = false;
	}

?>
