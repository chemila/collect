<?

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_DB2DB . ' ';
	$sql .= 'ORDER BY id DESC';
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$db->disconnect ();
	$i = 0;
	while ($rs->next_record ())
	{
		$list['list'][$i]['id'] = $rs->get ('id');
		$list['list'][$i]['name'] = $rs->get ('name');
		$list['list'][$i]['num'] = $rs->get ('num');
		++$i;
	}

	$tp->set_templatefile ('templates/db_list.html');
	$tp->assign ($list);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '导入配置列表';
?>
