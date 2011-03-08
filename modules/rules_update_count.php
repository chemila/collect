<?

	if (!$_GET['ID'])
	{
		error ('采集器编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('采集器编号只能是数字!');
	}

	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$countSql = 'SELECT COUNT(*) AS total ';
	$countSql .= 'FROM ' . TB_LINKS . ' ';
	$countSql .= 'WHERE rules = ' . $_GET['ID'];
	$rs = $db->query ($countSql);
	$rs->next_record ();
	$linkTotal = $rs->get ('total');
	$countSql = 'SELECT COUNT(*) AS total ';
	$countSql .= 'FROM ' . TB_DATA . ' ';
	$countSql .= 'WHERE rules = ' . $_GET['ID'];
	$rs = $db->query ($countSql);
	$rs->next_record ();
	$importTotal = $rs->get ('total');
	$NBS = new NEATBulidSql (TB_RULES);
	$conditionFids['id'] = $_GET['ID'];
	$rulesFids['link_num'] = $linkTotal;
	$rulesFids['import_num'] = $importTotal;
	$sql = $NBS->update ($rulesFids, $conditionFids);
	$db->update ($sql);
	$db->disconnect ();
	showloading ('?module=listRules', '更新计数成功', '编号为 ' . $_GET['ID'] . ' 的采集器更新计数,现在返回采集器列表.');
	$tpShowBody = false;
?>
