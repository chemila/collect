<?

	if (!$_GET['ID'])
	{
		error ('采集器编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('采集器编号只能是数字!');
	}

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_RULES . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$rs->next_record ();
	if (!$rs)
	{
		error ('没有找到需要的采集器');
	}

	if (!$_GET['action'])
	{
		$tp->set_templatefile ('templates/rule_copy.html');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('ruleName', $rs->get ('name'));
		$moduleTemplate = $tp->result ();
		$moduleTitle = '复制采集器';
		$db->disconnect ();
	}
	else
	{
		$newRuleName = trim ($_POST['newRuleName']);
		if (!$newRuleName)
		{
			error ('请输入新的采集器名称');
		}

		$sourceArray = $rs->getarray ();
		foreach ($sourceArray as $key => $val)
		{
			if ((((($key == 'name' OR $key == 'id') OR $key == 'num') OR $key == 'link_num') OR $key == 'import_num'))
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
		$sqlValue .= ' , \'' . $newRuleName . '\'';
		$insertSql = 'INSERT INTO ' . TB_RULES . ' ';
		$insertSql .= '(' . $sqlFids . ') ';
		$insertSql .= 'VALUES (' . $sqlValue . ') ';
		$db->query ($insertSql);
		$ruleID = $db->lastid ();
		$sql = 'SELECT * ';
		$sql .= 'FROM ' . TB_FILTER . ' ';
		$sql .= 'WHERE rule_id = ' . $_GET['ID'];
		$rsFilter = $db->query ($sql);
		while ($rsFilter->next_record ())
		{
			$filterSource[] = $rsFilter->getarray ();
		}

		if (is_array ($filterSource))
		{
			foreach ($filterSource as $val)
			{
				$insertSql = '';
				$sqlFids = '';
				$sqlValue = '';
				$sign = '';
				foreach ($val as $k => $v)
				{
					if (($k == 'id' OR $k == 'rule_id'))
					{
						continue;
					}
					else
					{
						$sqlFids .= $sign . $k;
						$sqlValue .= $sign . '\'' . addslashes ($v) . '\'';
						$sign = ', ';
						continue;
					}
				}

				$sqlFids .= ', rule_id';
				$sqlValue .= ' , \'' . $ruleID . '\'';
				$insertSql = 'INSERT INTO ' . TB_FILTER . ' ';
				$insertSql .= '(' . $sqlFids . ') ';
				$insertSql .= 'VALUES (' . $sqlValue . ') ';
				$db->query ($insertSql);
			}
		}

		showloading ('?module=listRules', '复制采集器成功', '新的采集器: ' . $newRuleName . ' 复制成功,现在采集器列表.');
		$tpShowBody = false;
		$db->disconnect ();
	}

?>
