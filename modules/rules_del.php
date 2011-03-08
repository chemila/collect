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
	if (!$rs->next_record ())
	{
		error ('找不到编号为' . $_GET['ID'] . '的采集器规则!');
	}

	$NBS = new NEATBulidSql (TB_RULES);
	$rulesFids['id'] = $_GET['ID'];
	$sql = $NBS->del ($rulesFids);
	$db->query ($sql);
	$NBS->setTable (TB_FILTER);
	$filterFids['rule_id'] = $_GET['ID'];
	$sql = $NBS->del ($filterFids);
	$db->query ($sql);
	$NBS->setTable (TB_LINKS);
	$linksFids['rules'] = $_GET['ID'];
	$sql = $NBS->del ($linksFids);
	$db->query ($sql);
	$NBS->setTable (TB_DATA);
	$dataFids['rules'] = $_GET['ID'];
	$sql = $NBS->del ($dataFids);
	$db->query ($sql);
	$db->disconnect ();
	showloading ('?module=listRules', '删除成功', '编号为 ' . $_GET['ID'] . ' 的采集器删除成功,现在返回采集器列表.');
	$tpShowBody = false;
?>
