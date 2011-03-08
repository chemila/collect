<?

	if ($_GET['rules'])
	{
		$sqlRules .= 'WHERE rules = ' . $_GET['rules'] . ' ';
		$sqlCountRules .= ' AND rules = ' . $_GET['rules'] . ' ';
	}

	include_once 'includes/class/basic/opb.class.php';
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$sql = 'SELECT COUNT(*) AS total ';
	$sql .= 'FROM ' . TB_LINKS . ' ';
	$sql .= $sqlRules;
	$rs = $db->query ($sql);
	$rs->next_record ();
	$total = $rs->get ('total');
	$onepage = NUM_LINK_ONEPAGE;
	($_GET['page'] ? $page = $_GET['page'] : $page = 1);
	$offset = $page * $onepage - $onepage;
	$pb = new OPB ($total, $onepage, 'searchkey');
	$pagebar = $pb->whole_bar ();
	$sql = 'SELECT d.*, r.name AS rules_name ';
	$sql .= 'FROM ' . TB_LINKS . ' AS d ';
	$sql .= 'LEFT JOIN ' . TB_RULES . ' AS r ';
	$sql .= 'ON d.rules = r.id ';
	$sql .= $sqlRules;
	$sql .= 'ORDER BY d.rules DESC, d.id DESC ';
	$rs = $db->query ($sql, $offset, $onepage);
	$i = 0;
	while ($rs->next_record ())
	{
		$title = $rs->get ('title');
		$list['list'][$i]['fullTitle'] = $title;
		$title_js = str_replace ('\'', '\\\'', $title);
		$title_js = str_replace ('"', '\\\'', $title_js);
		$list['list'][$i]['title_js'] = chop ($title_js);
		if (40 < strlen ($title))
		{
			$title = m_substr ($title, 0, 40) . chr (0) . '...';
		}

		$list['list'][$i]['id'] = $rs->get ('id');
		$list['list'][$i]['title'] = $title;
		$list['list'][$i]['url'] = $rs->get ('url');
		$list['list'][$i]['date'] = date ('Y-m-d', $rs->get ('date'));
		$list['list'][$i]['rules'] = $rs->get ('rules_name');
		if ($rs->get ('adopt') == 1)
		{
			$list['list'][$i]['adopt_str'] = '是';
			$list['list'][$i]['adopt_change'] = 'no';
			$list['list'][$i]['adopt_bgcolor'] = '';
		}
		else
		{
			$list['list'][$i]['adopt_str'] = '否';
			$list['list'][$i]['adopt_change'] = 'yes';
			$list['list'][$i]['adopt_bgcolor'] = '#EFEFEF';
		}

		if ($rs->get ('import') == 1)
		{
			$list['list'][$i]['import_str'] = '是';
			$list['list'][$i]['import_bgcolor'] = '#EFEFEF';
		}
		else
		{
			$list['list'][$i]['import_str'] = '否';
			$list['list'][$i]['import_bgcolor'] = '';
		}

		++$i;
	}

	$sql = 'SELECT id, name ';
	$sql .= 'FROM ' . TB_RULES . ' ';
	$sql .= 'ORDER BY id DESC';
	$rs = $db->query ($sql);
	$i = 0;
	while ($rs->next_record ())
	{
		$list['option'][$i]['rulesID'] = $rs->get ('id');
		$list['option'][$i]['rulesName'] = $rs->get ('name');
		($rs->get ('id') == $_GET['rules'] ? $list['option'][$i]['rulesSelected'] = ' selected' : $list['option'][$i]['rulesSelected'] = '');
		++$i;
	}

	$sql = 'SELECT COUNT(*) AS total ';
	$sql .= 'FROM ' . TB_LINKS . ' ';
	$sql .= 'WHERE adopt = 1';
	$sql .= $sqlCountRules;
	$rs = $db->query ($sql);
	$rs->next_record ();
	$adopt = $rs->get ('total');
	$unadopt = $total - $adopt;
	$sql = 'SELECT COUNT(*) AS total ';
	$sql .= 'FROM ' . TB_LINKS . ' ';
	$sql .= 'WHERE import = 1';
	$sql .= $sqlCountRules;
	$rs = $db->query ($sql);
	$rs->next_record ();
	$import = $rs->get ('total');
	$unimport = $total - $import;
	$db->disconnect ();
	$tp->set_templatefile ('templates/link_list.html');
	$tp->assign ($list);
	$tp->assign ('total', $total);
	$tp->assign ('adopt', $adopt);
	$tp->assign ('unadopt', $unadopt);
	$tp->assign ('unimport', $unimport);
	$tp->assign ('import', $import);
	$tp->assign ('rulesID', $_GET['rules']);
	$tp->assign ('pageBar', $pagebar);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '连接库列表';
?>
