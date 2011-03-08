<?

	if ($_GET['rules'])
	{
		$sqlRules .= 'WHERE rules = ' . $_GET['rules'] . ' ';
	}

	include_once 'includes/class/basic/opb.class.php';
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$sql = 'SELECT COUNT(*) AS total ';
	$sql .= 'FROM ' . TB_DATA . ' ';
	$sql .= $sqlRules;
	$rs = $db->query ($sql);
	$rs->next_record ();
	$total = $rs->get ('total');
	$onepage = NUM_IMPORT_ONEPAGE;
	($_GET['page'] ? $page = $_GET['page'] : $page = 1);
	$offset = $page * $onepage - $onepage;
	$pb = new OPB ($total, $onepage, 'searchkey');
	$pagebar = $pb->whole_bar ();
	$sql = 'SELECT d.title, d.rules, d.date, d.img_geted, d.id, d.link_id, r.name AS rules_name ';
	$sql .= 'FROM ' . TB_DATA . ' AS d ';
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
		$list['list'][$i]['link_id'] = $rs->get ('link_id');
		$list['list'][$i]['title'] = $title;
		$list['list'][$i]['date'] = date ('Y-m-d', $rs->get ('date'));
		$list['list'][$i]['rules'] = $rs->get ('rules_name');
		if ($rs->get ('img_geted'))
		{
			$list['list'][$i]['img_geted'] = '是';
			$list['list'][$i]['img_geted_bg'] = '#EFEFEF';
		}
		else
		{
			$list['list'][$i]['img_geted'] = '否';
			$list['list'][$i]['img_geted_bg'] = '#FFFFFF';
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

	$db->disconnect ();
	$tp->set_templatefile ('templates/import_list.html');
	$tp->assign ($list);
	$tp->assign ('total', $total);
	$tp->assign ('adopt', $adopt);
	$tp->assign ('unadopt', $unadopt);
	$tp->assign ('unimport', $unimport);
	$tp->assign ('import', $import);
	$tp->assign ('rulesID', $_GET['rules']);
	$tp->assign ('pageBar', $pagebar);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '文章库列表';
?>
