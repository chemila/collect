<?

	if (!$_GET['ID'])
	{
		error ('导入配置编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('导入配置编号只能是数字!');
	}

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_DB2DB . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$rs->next_record ();
	if (!$rs)
	{
		error ('没有找到需要的导入配置');
	}

	if (!$_GET['action'])
	{
		$tp->set_templatefile ('templates/db_cfg_copy.html');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('cfgname', $rs->get ('name'));
		$moduleTemplate = $tp->result ();
		$moduleTitle = '复制导入配置';
		$db->disconnect ();
	}
	else
	{
		$configName = trim ($_POST['configname']);
		if (!$_POST['configname'])
		{
			error ('请输入配置名称');
		}

		$sourceArray = $rs->getarray ();
		foreach ($sourceArray as $key => $val)
		{
			if (($key == 'name' OR $key == 'id'))
			{
				continue;
			}
			else
			{
				$sqlFids .= $sign . $key;
				$sqlValue .= $sign . '\'' . addslashes ($val) . '\'';
				$sign = ', ';
				continue;
			}
		}

		$sqlFids .= ', name';
		$sqlValue .= ' , \'' . addslashes ($configName) . '\'';
		$insertSql = 'INSERT INTO ' . TB_DB2DB . ' ';
		$insertSql .= '(' . $sqlFids . ') ';
		$insertSql .= 'VALUES (' . $sqlValue . ') ';
		$db->query ($insertSql);
		showloading ('?module=listDB', '复制导入配置成功', '新的导入配置: ' . $configName . ' 复制成功,现在导入配置列表.');
		$tpShowBody = false;
		$db->disconnect ();
	}

?>
