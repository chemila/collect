<?

	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$type['yes'] = 1;
	$type['no'] = 0;
	$message['yes'] = '采用';
	$message['no'] = '禁用';
	$link_adopt = $type[$_GET['adopt']];
	if ($link_adopt == 0)
	{
		$adoptSql = ' adopt = 0 , import = 0 ';
	}
	else
	{
		$adoptSql = ' adopt =1 ';
	}

	if ((!$_GET['rules'] OR !is_numeric ($_GET['rules'])))
	{
		if ((!$_GET['ID'] AND !$_POST['ID']))
		{
			error ('你要更新哪个连接的采用情况?你可是一条都没有选择哦！');
		}

		(!$_GET['ID'] ? $id = $_POST['ID'] : $id = $_GET['ID']);
		if (is_array ($id))
		{
			foreach ($id as $k => $v)
			{
				$adoptID[] = $v;
			}
		}
		else
		{
			if (is_numeric ($id))
			{
				$adoptID[] = $id;
			}
			else
			{
				error ('连接编号只能是数字');
			}
		}

		$idNum = count ($adoptID);
		foreach ($adoptID as $k => $v)
		{
			++$i;
			$sqlIDList .= $v;
			if ($i < $idNum)
			{
				$sqlIDList .= ', ';
				continue;
			}
		}

		$updateSql = 'UPDATE ' . TB_LINKS . ' SET ';
		$updateSql .= $adoptSql;
		$updateSql .= '	WHERE id IN (' . $sqlIDList . ')';
		$db->update ($updateSql);
		$sql = 'DELETE ';
		$sql .= 'FROM ' . TB_DATA . ' ';
		$sql .= 'WHERE link_id ';
		$sql .= 'IN (' . $sqlIDList . ')';
		$db->update ($sql);
	}
	else
	{
		$totalSql = 'SELECT COUNT(*) AS total FROM ' . TB_LINKS . ' ';
		$totalSql .= ' WHERE rules = ' . $_GET['rules'] . ' ';
		$totalrs = $db->query ($totalSql);
		$totalrs->next_record ();
		$idNum = $totalrs->get ('total');
		$updateSql = 'UPDATE ' . TB_LINKS . ' SET ';
		$updateSql .= $adoptSql;
		$updateSql .= '	WHERE rules = ' . $_GET['rules'] . ' ';
		$db->update ($updateSql);
		$sql = 'DELETE ';
		$sql .= 'FROM ' . TB_DATA . ' ';
		$sql .= 'WHERE rules ';
		$sql .= '= ' . $_GET['rules'] . ' ';
		$db->update ($sql);
	}

	$db->disconnect ();
	($_SERVER['HTTP_REFERER'] ? $url = $_SERVER['HTTP_REFERER'] : $url = '?module=listLink');
	showloading ($url, '编辑成功', '有 ' . $idNum . ' 条连接更新为 ' . $message[$_GET['adopt']] . ' ,现在返回连接列表.', 1);
	$tpShowBody = false;
?>
