<?

	if ((!$_GET['ID'] AND !$_POST['ID']))
	{
		error ('你要删除哪个连接?');
	}

	(!$_GET['ID'] ? $id = $_POST['ID'] : $id = $_GET['ID']);
	if (is_array ($id))
	{
		foreach ($id as $k => $v)
		{
			$delID[] = $v;
		}
	}
	else
	{
		$delID[] = $id;
	}

	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$idNum = count ($delID);
	foreach ($delID as $k => $v)
	{
		++$i;
		$sqlIDList .= $v;
		if ($i < $idNum)
		{
			$sqlIDList .= ', ';
			continue;
		}
	}

	$sql = 'SELECT rules ';
	$sql .= 'FROM ' . TB_LINKS . ' ';
	$sql .= 'WHERE id ';
	$sql .= 'IN (' . $sqlIDList . ')';
	$rs = $db->query ($sql);
	$count = array ();
	while ($rs->next_record ())
	{
		$rulesID = $rs->get ('rules');
		++$count[$rulesID];
	}

	$NBS = new NEATBulidSql (TB_RULES);
	foreach ($count as $k => $v)
	{
		$updateFids['link_num'] = 'num';
		$conditionFids['id'] = $k;
		$config['link_num']['method'] = '-';
		$config['link_num']['num'] = $v;
		$sql = $NBS->update ($updateFids, $conditionFids, $config);
		$db->update ($sql);
	}

	$sql = 'DELETE ';
	$sql .= 'FROM ' . TB_DATA . ' ';
	$sql .= 'WHERE link_id ';
	$sql .= 'IN (' . $sqlIDList . ')';
	$db->update ($sql);
	$sql = 'DELETE ';
	$sql .= 'FROM ' . TB_LINKS . ' ';
	$sql .= 'WHERE id ';
	$sql .= 'IN (' . $sqlIDList . ')';
	$db->update ($sql);
	$db->disconnect ();
	($_SERVER['HTTP_REFERER'] ? $url = $_SERVER['HTTP_REFERER'] : $url = '?module=listLink');
	showloading ($url, '连接删除成功', '有 ' . $idNum . ' 条连接被成功删除.现在返回连接列表');
	$tpShowBody = false;
?>
