<?

	if (!$_GET['ID'])
	{
		error ('文章编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('文章编号只能是数字!');
	}

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_DATA . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$rs->next_record ();
	$db->disconnect ();
	$shortURL = $rs->get ('url');
	$fullURL = $shortURL;
	if (80 < strlen ($shortURL))
	{
		$shortURL = c_substr ($shortURL, 0, 80) . '...';
	}

	$tp->set_templatefile ('templates/import_view.html');
	$tp->assign ('title', $rs->get ('title'));
	$tp->assign ('date', date ('Y-m-d H:i:s', $rs->get ('date')));
	$tp->assign ('body', $rs->get ('body'));
	$tp->assign ('author', $rs->get ('author'));
	$tp->assign ('from', $rs->get ('data_from'));
	$tp->assign ('intro', $rs->get ('intro'));
	$tp->assign ('url', $shortURL);
	$tp->assign ('fullURL', $fullURL);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '浏览入库数据';
?>
