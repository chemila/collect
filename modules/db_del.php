<?

	if (!$_GET['ID'])
	{
		error ('导入配置编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('导入配置编号只能是数字!');
	}

	$sql = 'DELETE ';
	$sql .= 'FROM ' . TB_DB2DB . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->update ($sql);
	$db->disconnect ();
	showloading ('?module=listDB', '删除成功...', '编号为' . $_GET['ID'] . '的导入规则已经被成功删除,现在返回导入规则列表');
	$tpShowBody = false;
?>
