<?

	if (!$_GET['ID'])
	{
		error ('采集器编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('采集器编号只能是数字!');
	}

	if (!$_GET['action'])
	{
		showloading ('?module=testBody&action=get&ID=' . $_GET['ID'] . '&url=' . rawurlencode ($_GET['url']), '测试采集内容中...');
		$tpShowBody = false;
	}
	else
	{
		if ($_GET['action'] == 'get')
		{
			$sql = 'SELECT * ';
			$sql .= 'FROM ' . TB_RULES . ' ';
			$sql .= 'WHERE id = ' . $_GET['ID'];
			$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
			$rs = $db->query ($sql);
			$filterSql = 'SELECT * ';
			$filterSql .= 'FROM ' . TB_FILTER . ' ';
			$filterSql .= 'WHERE rule_id = ' . $_GET['ID'];
			$rsFilter = $db->query ($filterSql);
			$i = 0;
			while ($rsFilter->next_record ())
			{
				$filter[$i] = $rsFilter->get ('filter_rule');
				++$i;
			}

			$db->disconnect ();
			if (!$rs->next_record ())
			{
				error ('找不到编号为' . $_GET['ID'] . '的采集器规则!');
			}

			$shortURL = $_GET['url'];
			$fullURL = $shortURL;
			if (80 < strlen ($shortURL))
			{
				$shortURL = c_substr ($shortURL, 0, 80) . '...';
			}

			$tag['variable'] = '[变数]';
			$tag['title'] = '[标题]';
			$tag['body'] = '[内容]';
			$tag['link'] = '[连接]';
			$tag['body_page'] = '[分页区域]';
			$tag['author'] = '[作者]';
			$tag['from'] = '[来源]';
			$tag['intro'] = '[简介]';
			$area['title'] = $rs->get ('area_title');
			$area['body'] = $rs->get ('area_body');
			$area['body_page'] = $rs->get ('area_body_page');
			$area['body_page_link'] = $rs->get ('area_body_page_link');
			$area['author'] = $rs->get ('area_author');
			$area['from'] = $rs->get ('area_from');
			$area['intro'] = $rs->get ('area_intro');
			$area['filter'] = $filter;
			($rs->get ('multi_title') == 1 ? $areaMulti['title'] = 1 : $areaMulti['title'] = 2);
			($rs->get ('multi_body') == 1 ? $areaMulti['body'] = 1 : $areaMulti['body'] = 2);
			($rs->get ('multi_body_page') == 1 ? $areaMulti['body_page'] = 1 : $areaMulti['body_page'] = 2);
			($rs->get ('multi_body_page_link') == 1 ? $areaMulti['body_page_link'] = 1 : $areaMulti['body_page_link'] = 2);
			($rs->get ('multi_author') == 1 ? $areaMulti['author'] = 1 : $areaMulti['author'] = 2);
			($rs->get ('multi_from') == 1 ? $areaMulti['from'] = 1 : $areaMulti['from'] = 2);
			($rs->get ('multi_intro') == 2 ? $areaMulti['intro'] = 1 : $areaMulti['intro'] = 2);
			($rs->get ('enter_title') == 0 ? $areaFormat['title'] = 1 : $areaFormat['title'] = 2);
			($rs->get ('enter_body') == 0 ? $areaFormat['body'] = 1 : $areaFormat['body'] = 2);
			($rs->get ('enter_body_page') == 0 ? $areaFormat['body_page'] = 1 : $areaFormat['body_page'] = 2);
			($rs->get ('enter_body_page_link') == 0 ? $areaFormat['body_page_link'] = 1 : $areaFormat['body_page_link'] = 2);
			($rs->get ('enter_author') == 0 ? $areaFormat['author'] = 1 : $areaFormat['author'] = 2);
			($rs->get ('enter_from') == 0 ? $areaFormat['from'] = 1 : $areaFormat['from'] = 2);
			($rs->get ('enter_intro') == 0 ? $areaFormat['intro'] = 1 : $areaFormat['intro'] = 2);
			$bodyPageType = $rs->get ('body_page_type');
			$method = 'GET';
			$param['cookie'] = $rs->get ('cookies');
//
		$referer = $rs->get ('referer');
			$useragent = $rs->get ('useragent');
		$replaceRNT = $rs->get ('replaceRNT');

			$NC = new NEAT_COLLECTOR ();
			$NIA = new NEAT_IMPORT_ARTICLE ($NC);
			$articleData = $NIA->getArticle ($fullURL, $tag, $area, $areaMulti, $areaFormat, $method, $param, $bodyPageType, $referer, $useragent, $replaceRNT);
			$tp->set_templatefile ('templates/import_view.html');
			$tp->assign ('title', deletehtml ($articleData['title']));
			$tp->assign ('date', date ('Y年m月d日'));
			$tp->assign ('body', $articleData['body']);
			$tp->assign ('author', $articleData['author']);
			$tp->assign ('from', $articleData['from']);
			$tp->assign ('intro', $articleData['intro']);
			$tp->assign ('url', $shortURL);
			$tp->assign ('fullURL', $fullURL);
			$moduleTemplate = $tp->result ();
			$moduleTitle = $articleData['title'];
		}
	}

?>
