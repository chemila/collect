<?

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
			($_GET['CID'] == $val['id'] ? $selected = ' selected' : $selected = '');
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

		$tp->set_templatefile ('templates/rules_form.html');
		$tp->assign ('indexType_I', '');
		$tp->assign ('indexType_II', '');
		$tp->assign ('indexType_III', '');
		$tp->assign ('url_I', '');
		$tp->assign ('url_II', '');
		$tp->assign ('url_III', '');
		$tp->assign ('page_start', '');
		$tp->assign ('page_end', '');
		$tp->assign ('methodType_GET', 'checked');
		$tp->assign ('area_link', '');
		$tp->assign ('area_title', '');
		$tp->assign ('area_body', '');
		$tp->assign ('multi_link', 'checked');
		$tp->assign ('multi_title', 'checked');
		$tp->assign ('multi_body', 'checked');
		$tp->assign ('multi_body_page', 'checked');
		$tp->assign ('multi_body_page_link', 'checked');
		$tp->assign ('multi_author', 'checked');
		$tp->assign ('multi_from', 'checked');
		$tp->assign ('multi_intro', 'checked');
		$tp->assign ('enter_link', '');
		$tp->assign ('enter_title', '');
		$tp->assign ('enter_body', '');
		$tp->assign ('enter_body_page', '');
		$tp->assign ('enter_body_page_link', '');
		$tp->assign ('enter_author', '');
		$tp->assign ('enter_from', '');
		$tp->assign ('enter_intro', '');
		$tp->assign ('body_page_type_all', 'checked');
		$tp->assign ('next_link_display', 'none');
		$tp->assign ('option', $option);
		$rulesFromPage = $tp->result ();
		$tp->set_templatefile ('templates/rules_add.html');
		$tp->assign ('rules_from', $rulesFromPage);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '添加采集器';
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

		if (!intval ($_POST['indexType']))
		{
			error ('请选择连接类型');
		}

		if (((!trim ($_POST['url_I']) AND !trim ($_POST['url_II'])) AND !trim ($_POST['url_III'])))
		{
			error ('请输入索引地址');
		}

		$urlType[1] = 'I';
		$urlType[2] = 'II';
		$urlType[3] = 'III';
		$url = 'url_' . $urlType[trim ($_POST['indexType'])];
		$NBS = new NEATBulidSql (TB_RULES);
		$rulesFids['id'] = '';
		$rulesFids['cid'] = intval ($_POST['pid']);
		$rulesFids['name'] = trim ($_POST['name']);
		$rulesFids['index_type'] = trim ($_POST['indexType']);
		$rulesFids['url'] = trim ($_POST[$url]);
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
		$rulesFids['link_num'] = 0;
		$rulesFids['import_num'] = 0;
		$rulesFids['date'] = strtotime (date ('Y-m-d'));
		$rulesFids['body_page_type'] = intval ($_POST['body_page_type']);
		$sql = $NBS->add ($rulesFids);
		$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$db->query ($sql);
		$NBS->setTable (TB_FILTER);
		$roleID = $db->lastid ();
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
					$tmpFilterFids['rule_id'] = $roleID;
					$sql = $NBS->add ($tmpFilterFids);
					$db->query ($sql);
					continue;
				}
			}
		}

		$db->disconnect ();
		showloading ('?module=listRules', '添加成功', '采集器: ' . $_POST['name'] . ' 添加成功,现在返回采集器列表.');
		$tpShowBody = false;
	}

?>
