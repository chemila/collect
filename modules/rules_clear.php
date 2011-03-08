<?

	$_GET['ID'] = intval ($_GET['ID']);
	if (!$_GET['ID'])
	{
		error ('请选择要清空的采集器');
	}

	$NDB = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	if (!$_GET['action'])
	{
		$sql = 'SELECT r.id, r.name, r.link_num, r.import_num, r.cid, c.title AS cateName ';
		$sql .= 'FROM ' . TB_RULES . ' AS r ';
		$sql .= 'LEFT JOIN ' . TB_CATE . ' AS c ';
		$sql .= 'ON r.cid = c.id ';
		$sql .= 'WHERE r.id = ' . $_GET['ID'];
		$rs = $NDB->query ($sql);
		if (!$rs->next_record ())
		{
			error ('没找到您要清空的采集器');
		}

		$tp->set_templatefile ('templates/rules_clear.html');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('ruleName', $rs->get ('name'));
		$tp->assign ('cateName', $rs->get ('cateName'));
		$tp->assign ('linkNum', $rs->get ('link_num'));
		$tp->assign ('importNum', $rs->get ('import_num'));
		$moduleTemplate = $tp->result ();
		$moduleTitle = '清空采集器数据';
	}
	else
	{
		$NBS = new NEATBulidSql (TB_LINKS);
		if ($_POST['link'])
		{
			$NBS->setTable (TB_LINKS);
			$linkFids['rules'] = $_GET['ID'];
			$sql = $NBS->del ($linkFids);
			$NDB->query ($sql);
			$NBS->setTable (TB_RULES);
			$conditionFids['id'] = $_GET['ID'];
			$rulesFids['link_num'] = 0;
			$sql = $NBS->update ($rulesFids, $conditionFids);
			$NDB->update ($sql);
		}

		if ($_POST['data'])
		{
			$NBS->setTable (TB_DATA);
			$dataFids['rules'] = $_GET['ID'];
			$sql = $NBS->del ($dataFids);
			$NDB->query ($sql);
			$NBS->setTable (TB_RULES);
			$conditionFids['id'] = $_GET['ID'];
			$rulesFids['import_num'] = 0;
			$sql = $NBS->update ($rulesFids, $conditionFids);
			$NDB->update ($sql);
		}

		showloading ('?module=listRules', '清空成功', '编号为 ' . $_GET['ID'] . ' 的采集器的数据清空成功,现在返回采集器列表.');
		$tpShowBody = false;
	}

	$NDB->disconnect ();
?>
