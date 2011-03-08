<?


	function build_xml ($arr, $endMark = '', $depth = 0)
	{
		global $XMLContent;
		global $CNTag;
		$check = true;
		if ($arr[0])
		{
			$check = false;
		}

		foreach ($arr as $k => $v)
		{
			if (is_array ($v))
			{
				if ($v[0])
				{
					build_xml ($v, $k, $depth + 1);
					continue;
				}
				else
				{
					if (is_numeric ($k))
					{
						$XMLContent .= str_repeat (' ', $depth) . '<' . $CNTag[$endMark] . '>
';
						build_xml ($v, $endMark, $depth + 1);
						continue;
					}
					else
					{
						$XMLContent .= '<' . $CNTag[$k] . '>
';
						build_xml ($v, $k, $depth + 1);
						continue;
					}

					continue;
				}

				continue;
			}
			else
			{
				if ((is_numeric ($v) OR !$v))
				{
					$contents = $v;
				}
				else
				{
					$contents = '<![CDATA[' . $v . ']]>';
				}

				$XMLContent .= str_repeat (' ', $depth) . '<' . $CNTag[$k] . '>' . $contents . '</' . $CNTag[$k] . '>
';
				continue;
			}
		}

		if (($endMark AND $check))
		{
			$XMLContent .= str_repeat (' ', $depth - 1) . '</' . $CNTag[$endMark] . '>
';
		}

	}

	$CNTag = array ('nc_rules_config' => '易采_采集器配置', 'information' => '配置信息', 'version' => '软件版本', 'config_name' => '配置名称', 'author' => '作者名字', 'contact' => '联系方式', 'readme' => '作者说明', 'date' => '导出时间', 'index' => '索引设置', 'index_type' => '索引类型', 'index_url' => '索引地址', 'page_start' => '开始页码', 'page_end' => '结束页码', 'link_replace' => '连接替换规则', 'http' => 'http设置', 'method' => '提交方式', 'posts' => 'POST内容', 'cookies' => 'Cookie内容', 'page' => '分页设置', 'page_type' => '分页类型', 'page_area' => '分页规则', 'page_next' => '下页规则', 'page_multi_page' => '分页规则多行匹配', 'page_multi_next' => '下页规则多行匹配', 'page_enter_page' => '分页规则UNIX格式', 'page_enter_next' => '下页规则UNIX格式', 'area' => '区域设置', 'area_link' => '连接区域', 'area_title' => '标题区域', 'area_body' => '内容区域', 'area_author' => '作者区域', 'area_from' => '来源区域', 'area_intro' => '简介区域', 'multi' => '多行设置', 'multi_link' => '连接区域多行匹配', 'multi_title' => '标题区域多行匹配', 'multi_body' => '内容区域多行匹配', 'multi_author' => '作者区域多行匹配', 'multi_from' => '来源区域多行匹配', 'multi_intro' => '简介区域多行匹配', 'enter' => 'UNIX格式设置', 'enter_link' => '连接区域UNIX格式', 'enter_title' => '标题区域UNIX格式', 'enter_body' => '内容区域UNIX格式', 'enter_author' => '作者区域UNIX格式', 'enter_from' => '来源区域UNIX格式', 'enter_intro' => '简介区域UNIX格式', 'filter_list' => '过滤器列表', 'filter' => '过滤器', 'filter_name' => '过滤器名字', 'filter_area' => '过滤器规则', 'filter_multi' => '过滤器规则多行匹配', 'filter_enter' => '过滤器规则UNIX格式');

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

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_RULES . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$rs = $db->query ($sql);
	if (!$rs->next_record ())
	{
		error ('找不到编号为' . $_GET['ID'] . '的采集器规则!');
	}

	if (!$_GET['action'])
	{
		$tp->set_templatefile ('templates/rules_export.html');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('config_name', $rs->get ('name'));
		$moduleTemplate = $tp->result ();
		$moduleTitle = '采集器配置导出';
		$db->disconnect ();
	}
	else
	{
		$cfgArray['information']['version'] = NEAT_COLLECTOR_VERSION;
		$cfgArray['information']['config_name'] = trim ($_POST['config_name']);
		$cfgArray['information']['author'] = trim ($_POST['author']);
		$cfgArray['information']['contact'] = trim ($_POST['contact']);
		$cfgArray['information']['readme'] = trim ($_POST['readme']);
		$cfgArray['information']['date'] = date ('Y-m-d H:i:s');
		$cfgArray['index']['index_type'] = $rs->get ('index_type');
		$cfgArray['index']['index_url'] = $rs->get ('url');
		$cfgArray['index']['page_start'] = $rs->get ('page_start');
		$cfgArray['index']['page_end'] = $rs->get ('page_end');
		$cfgArray['index']['link_replace'] = $rs->get ('link_replace');
		$cfgArray['http']['method'] = $rs->get ('method');
		$cfgArray['http']['posts'] = $rs->get ('posts');
		$cfgArray['http']['cookies'] = $rs->get ('cookies');
		$cfgArray['page']['page_type'] = $rs->get ('body_page_type');
		$cfgArray['page']['page_area'] = $rs->get ('area_body_page');
		$cfgArray['page']['page_next'] = $rs->get ('area_body_page_link');
		$cfgArray['page']['page_multi_page'] = $rs->get ('multi_body_page');
		$cfgArray['page']['page_multi_next'] = $rs->get ('multi_body_page_link');
		$cfgArray['page']['page_enter_page'] = $rs->get ('enter_body_page');
		$cfgArray['page']['page_enter_next'] = $rs->get ('enter_body_page_link');
		$cfgArray['area']['area_link'] = $rs->get ('area_link');
		$cfgArray['area']['area_title'] = $rs->get ('area_title');
		$cfgArray['area']['area_body'] = $rs->get ('area_body');
		$cfgArray['area']['area_author'] = $rs->get ('area_author');
		$cfgArray['area']['area_from'] = $rs->get ('area_from');
		$cfgArray['area']['area_intro'] = $rs->get ('area_intro');
		$cfgArray['multi']['multi_link'] = $rs->get ('multi_link');
		$cfgArray['multi']['multi_title'] = $rs->get ('multi_title');
		$cfgArray['multi']['multi_body'] = $rs->get ('multi_body');
		$cfgArray['multi']['multi_author'] = $rs->get ('multi_author');
		$cfgArray['multi']['multi_from'] = $rs->get ('multi_from');
		$cfgArray['multi']['multi_intro'] = $rs->get ('multi_intro');
		$cfgArray['enter']['enter_link'] = $rs->get ('enter_link');
		$cfgArray['enter']['enter_title'] = $rs->get ('enter_title');
		$cfgArray['enter']['enter_body'] = $rs->get ('enter_body');
		$cfgArray['enter']['enter_author'] = $rs->get ('enter_author');
		$cfgArray['enter']['enter_from'] = $rs->get ('enter_from');
		$cfgArray['enter']['enter_intro'] = $rs->get ('enter_intro');
		$sql = 'SELECT * ';
		$sql .= 'FROM ' . TB_FILTER . ' ';
		$sql .= 'WHERE rule_id = ' . $_GET['ID'] . ' ';
		$sql .= 'ORDER BY id ASC';
		$rsFilter = $db->query ($sql);
		$i = 0;
		while ($rsFilter->next_record ())
		{
			$cfgArray['filter_list']['filter'][$i]['filter_name'] = $rsFilter->get ('filter_name');
			$cfgArray['filter_list']['filter'][$i]['filter_area'] = $rsFilter->get ('filter_rule');
			$cfgArray['filter_list']['filter'][$i]['filter_multi'] = $rsFilter->get ('filter_multi');
			$cfgArray['filter_list']['filter'][$i]['filter_enter'] = $rsFilter->get ('filter_enter');
			++$i;
		}

		$db->disconnect ();
		$allArrry['nc_rules_config'] = $cfgArray;
		$XMLContent = '';
		build_xml ($allArrry);
		$XMLContent = '<?xml version="1.0" encoding="utf-8"?>
' . $XMLContent;
		$fileName = str_replace ('/', '', $_POST['config_name']);
		$fileName = str_replace ('\\', '', $fileName);
		$fileName = str_replace ('"', '', $fileName);
		$fileName = str_replace ('?', '', $fileName);
		$fileName = str_replace ('*', '', $fileName);
		$fileName = str_replace ('|', '', $fileName);
		$fileName = str_replace ('<', '', $fileName);
		$fileName = str_replace ('>', '', $fileName);
		ob_end_clean ();
		header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
		header ('Content-Encoding: none');
		header ('Cache-Control: private');
		header ('Content-Length: ' . strlen ($XMLContent));
		header ('Content-Disposition: inline;filename= ' . trim ($fileName) . '.cfg');
		header ('Content-Type: cfg');
		echo $XMLContent;
		exit ();
	}

?>
