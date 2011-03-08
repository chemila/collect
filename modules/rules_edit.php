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
	if (!$_GET['action'])
	{
		include_once 'includes/class/basic/NEATCategory.class.php';
		include_once 'includes/class/basic/NEATCache.class.php';
		$NC = new NEAT_CATEGORY ();
		$NBS = new NEATBulidSql (TB_CATE);
		$NCA = new NEAT_CACHE ();
		$NDB = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$NCA->setCachePath ('tmp/');
		$NCA->setCacheFile ('categoryCache');
		$NC->setTable (TB_CATE);
		$catData['id'] = 'id';
		$catData['pid'] = 'pid';
		$catData['orderid'] = 'orderid';
		$NC->setField ($catData);
		$NC->setNDB ($NDB);
		$NC->setNBS ($NBS);
		$NC->setNCA ($NCA);
		$sql = 'SELECT * ';
		$sql .= 'FROM ' . TB_RULES . ' ';
		$sql .= 'WHERE id = ' . $_GET['ID'];
		$rs = $db->query ($sql);
		if (!$rs->next_record ())
		{
			error ('找不到编号为' . $_GET['ID'] . '的采集器规则!');
		}

		$getarray = $NC->readCategoryCache ();
		if (!is_array ($getarray))
		{
			$getarray = array ();
		}

		$last = '├─';
		$option = '<option value=0>无分类</option>';
		foreach ($getarray as $key => $val)
		{
			$itemTemp = str_repeat ('│', $val['deep']);
			($rs->get ('cid') == $val['id'] ? $selected = ' selected' : $selected = '');
			$option .= '<option' . $selected . ' value="' . $val['id'] . '">' . $itemTemp . $last . $val['title'] . '</option>
';
			$tree .= 'var item' . $val['id'] . ' =new treeItem("' . $val['title'] . '", \'?module=addCategory&CID=' . $val['id'] . '\');
';
			if ($val['pid'] == 0)
			{
				$root .= 'root.add(item' . $val['id'] . ');
';
				continue;
			}
			else
			{
				$item .= 'item' . $val['pid'] . '.add(item' . $val['id'] . ');
';
				continue;
			}
		}

		$sql = 'SELECT * ';
		$sql .= 'FROM ' . TB_FILTER . ' ';
		$sql .= 'WHERE rule_id = \'' . $_GET['ID'] . '\' ';
		$sql .= 'ORDER BY id ASC';
		$rsFilter = $db->query ($sql);
		$i = 0;
		while ($rsFilter->next_record ())
		{
			$filter[$i]['id'] = $rsFilter->get ('id');
			$filter[$i]['filter_rule'] = html2text ($rsFilter->get ('filter_rule'));
			$filter[$i]['filter_name'] = html2text ($rsFilter->get ('filter_name'));
			if ($rsFilter->get ('filter_multi'))
			{
				$filter[$i]['filter_multi'] = 'checked';
			}

			if ($rsFilter->get ('filter_enter'))
			{
				$filter[$i]['filter_enter'] = 'checked';
			}

			++$i;
		}

		$tp->set_templatefile ('templates/rules_form.html');
		switch ($rs->get ('index_type'))
		{
			case 1:
			{
				$indexType = 'indexType_I';
				$url = 'url_I';
				break;
			}

			case 2:
			{
				$indexType = 'indexType_II';
				$url = 'url_II';
				break;
			}

			case 3:
			{
				$indexType = 'indexType_III';
				$url = 'url_III';
			}
		}

		switch ($rs->get ('method'))
		{
			case 1:
			{
				$methodType = 'methodType_GET';
				break;
			}

			case 2:
			{
				$methodType = 'methodType_POST';
				break;
			}

			default:
			{
				$methodType = 'methodType_GET';
			}
		}

	//获取repalecRNT 过滤空格 回车 TAB
	$replaceRNT = $rs->get ('replaceRNT');
	$replaceRNT = @explode(',', $replaceRNT );
		$replaceRNT[0] == 1 ? $replaceRNTType_R = 'checked' : $replaceRNTType_R = '';
		$replaceRNT[1] == 1 ? $replaceRNTType_N = 'checked' : $replaceRNTType_N = '';
		$replaceRNT[2] == 1 ? $replaceRNTType_T = 'checked' : $replaceRNTType_T = '';
	$replaceRNT[3] == 1 ? $replaceRNTType_D = 'checked' : $replaceRNTType_D = '';
	$replaceRNT[4] == 1 ? $replaceRNTType_C = 'checked' : $replaceRNTType_C = '';
	//获取page_rules [增加][补位] 
	$page_rules = $rs->get ('page_rules');
	$page_rules = @explode(',', $page_rules );
	$page_rules_mula =$page_rules[0];
		$page_rules_add =$page_rules[1];
		$page_rules_fill =$page_rules[2];
	//获取referer
	$referer = $rs->get ('referer');
	//获取user-agent
	$useragent = $rs->get ('useragent');

		($rs->get ('multi_link') == 1 ? $multi_link = 'checked' : $multi_link = '');
		($rs->get ('multi_title') == 1 ? $multi_title = 'checked' : $multi_title = '');
		($rs->get ('multi_body') == 1 ? $multi_body = 'checked' : $multi_body = '');
		($rs->get ('multi_body_page') == 1 ? $multi_body_page = 'checked' : $multi_body_page = '');
		($rs->get ('multi_body_page_link') == 1 ? $multi_body_page_link = 'checked' : $multi_body_page_link = '');
		($rs->get ('multi_author') == 1 ? $multi_author = 'checked' : $multi_author = '');
		($rs->get ('multi_from') == 1 ? $multi_from = 'checked' : $multi_from = '');
		($rs->get ('multi_intro') == 1 ? $multi_intro = 'checked' : $multi_intro = '');
		($rs->get ('enter_link') == 1 ? $enter_link = 'checked' : $enter_link = '');
		($rs->get ('enter_title') == 1 ? $enter_title = 'checked' : $enter_title = '');
		($rs->get ('enter_body') == 1 ? $enter_body = 'checked' : $enter_body = '');
		($rs->get ('enter_body_page') == 1 ? $enter_body_page = 'checked' : $enter_body_page = '');
		($rs->get ('enter_body_page_link') == 1 ? $enter_body_page_link = 'checked' : $enter_body_page_link = '');
		($rs->get ('enter_author') == 1 ? $enter_author = 'checked' : $enter_author = '');
		($rs->get ('enter_from') == 1 ? $enter_from = 'checked' : $enter_from = '');
		($rs->get ('enter_intro') == 1 ? $enter_intro = 'checked' : $enter_intro = '');
		($rs->get ('body_page_type') == 1 ? $body_page_type_next = 'checked' : $body_page_type_all = 'checked');
		($rs->get ('body_page_type') == 1 ? $next_link_display = 'block' : $next_link_display = 'none');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('name', $rs->get ('name'));
		$tp->assign ($indexType, 'checked');
		$tp->assign ($url, $rs->get ('url'));
		$tp->assign ($methodType, 'checked');
		$tp->assign ('posts', $rs->get ('posts'));
		$tp->assign ('cookies', $rs->get ('cookies'));
		$tp->assign ('page_start', $rs->get ('page_start'));
		$tp->assign ('page_end', $rs->get ('page_end'));
		$tp->assign ('link_replace', $rs->get ('link_replace'));
		$tp->assign ('area_link', html2text ($rs->get ('area_link')));
		$tp->assign ('area_title', html2text ($rs->get ('area_title')));
		$tp->assign ('area_body', html2text ($rs->get ('area_body')));
		$tp->assign ('area_body_page', html2text ($rs->get ('area_body_page')));
		$tp->assign ('area_body_page_link', html2text ($rs->get ('area_body_page_link')));
		$tp->assign ('area_author', html2text ($rs->get ('area_author')));
		$tp->assign ('area_from', html2text ($rs->get ('area_from')));
		$tp->assign ('area_intro', html2text ($rs->get ('area_intro')));
//
		$tp->assign ('replaceRNTType_R', $replaceRNTType_R);
		$tp->assign ('replaceRNTType_N', $replaceRNTType_N);
		$tp->assign ('replaceRNTType_T', $replaceRNTType_T);
		$tp->assign ('replaceRNTType_D', $replaceRNTType_D);
		$tp->assign ('replaceRNTType_C', $replaceRNTType_C);
//		
		$tp->assign ('page_rules_mula', $page_rules_mula); //乘法
		$tp->assign ('page_rules_add', $page_rules_add); //加法
		$tp->assign ('page_rules_fill', $page_rules_fill); //补位
//		
		$tp->assign ('useragent', $useragent); //乘法
	$tp->assign ('referer', $referer); //乘法

		$tp->assign ('multi_link', $multi_link);
		$tp->assign ('multi_title', $multi_title);
		$tp->assign ('multi_body', $multi_body);
		$tp->assign ('multi_body_page', $multi_body_page);
		$tp->assign ('multi_body_page_link', $multi_body_page_link);
		$tp->assign ('multi_author', $multi_author);
		$tp->assign ('multi_from', $multi_from);
		$tp->assign ('multi_intro', $multi_intro);
		$tp->assign ('enter_link', $enter_link);
		$tp->assign ('enter_title', $enter_title);
		$tp->assign ('enter_body', $enter_body);
		$tp->assign ('enter_body_page', $enter_body_page);
		$tp->assign ('enter_body_page_link', $enter_body_page_link);
		$tp->assign ('enter_author', $enter_author);
		$tp->assign ('enter_from', $enter_from);
		$tp->assign ('enter_intro', $enter_intro);
		$tp->assign ('filter', $filter);
		$tp->assign ('body_page_type_next', $body_page_type_next);
		$tp->assign ('body_page_type_all', $body_page_type_all);
		$tp->assign ('next_link_display', $next_link_display);
		$tp->assign ('option', $option);
		$rulesFromPage = $tp->result ();
		$tp->set_templatefile ('templates/rules_edit.html');
		$tp->assign ('rules_from', $rulesFromPage);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '编辑采集器规则';
	}
	else
	{
		if (!trim ($_POST['name']))
		{
			error ('请输入采集器名字');
		}

		if (!intval ($_POST['pid']))
		{
			error ('请选择采集器分类');
		}


//		从POST中合并replaceRNT
		$replaceRNT_ALL=trim($_POST['delr']).','.trim($_POST['deln']).','.trim($_POST['delt']).','.trim($_POST['debugshow']).','.trim($_POST['charset']);
		$page_rules=trim($_POST['page_mula']).','.trim($_POST['page_add']).','.trim($_POST['page_fill']);

		$urlType[1] = 'I';
		$urlType[2] = 'II';
		$urlType[3] = 'III';
		$url = 'url_' . $urlType[$_POST['indexType']];
		$NBS = new NEATBulidSql (TB_RULES);
		$conditionFids['id'] = $_GET['ID'];
		$rulesFids['cid'] = intval ($_POST['pid']);
		$rulesFids['name'] = trim ($_POST['name']);
		$rulesFids['index_type'] = trim ($_POST['indexType']);
		$rulesFids['url'] = trim ($_POST[$url]);
//		add type
		$rulesFids['replaceRNT'] = $replaceRNT_ALL;
		$rulesFids['page_rules'] = $page_rules;
		$rulesFids['useragent'] = trim ($_POST['useragent']);
		$rulesFids['referer'] = trim ($_POST['referer']);

		$rulesFids['method'] = trim ($_POST['method']);
		$rulesFids['posts'] = trim ($_POST['posts']);
		$rulesFids['cookies'] = trim ($_POST['cookies']);
		$rulesFids['page_start'] = intval ($_POST['page_start']);
		$rulesFids['page_end'] = intval ($_POST['page_end']);
		$rulesFids['link_replace'] = $_POST['link_replace'];
		$rulesFids['area_link'] = $_POST['area_link'];
		$rulesFids['area_title'] = $_POST['area_title'];
		$rulesFids['area_body'] = $_POST['area_body'];
		$rulesFids['area_body_page'] = $_POST['area_body_page'];
		$rulesFids['area_body_page_link'] = $_POST['area_body_page_link'];
		$rulesFids['area_author'] = $_POST['area_author'];
		$rulesFids['area_from'] = $_POST['area_from'];
		$rulesFids['area_intro'] = $_POST['area_intro'];
		$rulesFids['multi_link'] = intval ($_POST['multi_link']);
		$rulesFids['multi_title'] = intval ($_POST['multi_title']);
		$rulesFids['multi_body'] = intval ($_POST['multi_body']);
		$rulesFids['multi_body_page'] = intval ($_POST['multi_body_page']);
		$rulesFids['multi_body_page_link'] = intval ($_POST['multi_body_page_link']);
		$rulesFids['multi_author'] = intval ($_POST['multi_author']);
		$rulesFids['multi_from'] = intval ($_POST['multi_from']);
		$rulesFids['multi_intro'] = intval ($_POST['multi_intro']);
		$rulesFids['enter_link'] = intval ($_POST['enter_link']);
		$rulesFids['enter_title'] = intval ($_POST['enter_title']);
		$rulesFids['enter_body'] = intval ($_POST['enter_body']);
		$rulesFids['enter_body_page'] = intval ($_POST['enter_body_page']);
		$rulesFids['enter_body_page_link'] = intval ($_POST['enter_body_page_link']);
		$rulesFids['enter_author'] = intval ($_POST['enter_author']);
		$rulesFids['enter_from'] = intval ($_POST['enter_from']);
		$rulesFids['enter_intro'] = intval ($_POST['enter_intro']);
		$rulesFids['body_page_type'] = $_POST['body_page_type'];
		$sql = $NBS->update ($rulesFids, $conditionFids);
		$db->query ($sql);
		$NBS->setTable (TB_FILTER);
		if ($_POST['filter_rule'])
		{
			foreach ($_POST['filter_rule'] as $key => $val)
			{
				if (!$_POST['filter_del'][$key])
				{
					$tmpConditionFids['id'] = $key;
					$tmpFilterFids['filter_multi'] = $_POST['filter_multi'][$key];
					$tmpFilterFids['filter_enter'] = $_POST['filter_enter'][$key];
					$tmpFilterFids['filter_rule'] = $_POST['filter_rule'][$key];
					$tmpFilterFids['filter_name'] = $_POST['filter_name'][$key];
					$sql = $NBS->update ($tmpFilterFids, $tmpConditionFids);
					$db->query ($sql);
					continue;
				}
				else
				{
					$tmpDelFids['id'] = $key;
					$sql = $NBS->del ($tmpDelFids);
					$db->query ($sql);
					continue;
				}
			}
		}

		if ($_POST['add_filter_rule'])
		{
			foreach ($_POST['add_filter_rule'] as $k => $v)
			{
				if ($v)
				{
					$tmpFilterFids['filter_multi'] = $_POST['add_filter_multi'][$k];
					$tmpFilterFids['filter_enter'] = $_POST['add_filter_enter'][$k];
					$tmpFilterFids['filter_rule'] = $_POST['add_filter_rule'][$k];
					$tmpFilterFids['filter_name'] = $_POST['add_filter_name'][$k];
					$tmpFilterFids['rule_id'] = $_GET['ID'];
					$sql = $NBS->add ($tmpFilterFids);
					$db->query ($sql);
					continue;
				}
			}
		}

		showloading ('?module=listRules', '编辑成功', '采集器: ' . $_POST['name'] . ' 编辑成功,现在返回采集器列表.');
		$tpShowBody = false;
	}

	$db->disconnect ();
?>
