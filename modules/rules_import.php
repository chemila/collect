<?


	function encodestring ($string)
	{
		$string = str_replace ('
', '
', $string);
		return $string;
	}


	include_once 'includes/class/basic/NEATXML.class.php';
	$CNTag = array ('nc_rules_config' => '易采_采集器配置', 'information' => '配置信息', 'version' => '软件版本', 'config_name' => '配置名称', 'author' => '作者名字', 'contact' => '联系方式', 'readme' => '作者说明', 'date' => '导出时间', 'index' => '索引设置', 'index_type' => '索引类型', 'index_url' => '索引地址', 'page_start' => '开始页码', 'page_end' => '结束页码', 'link_replace' => '连接替换规则', 'http' => 'http设置', 'method' => '提交方式', 'posts' => 'POST内容', 'cookies' => 'Cookie内容', 'page' => '分页设置', 'page_type' => '分页类型', 'page_area' => '分页规则', 'page_next' => '下页规则', 'page_multi_page' => '分页规则多行匹配', 'page_multi_next' => '下页规则多行匹配', 'page_enter_page' => '分页规则UNIX格式', 'page_enter_next' => '下页规则UNIX格式', 'area' => '区域设置', 'area_link' => '连接区域', 'area_title' => '标题区域', 'area_body' => '内容区域', 'area_author' => '作者区域', 'area_from' => '来源区域', 'area_intro' => '简介区域', 'multi' => '多行设置', 'multi_link' => '连接区域多行匹配', 'multi_title' => '标题区域多行匹配', 'multi_body' => '内容区域多行匹配', 'multi_author' => '作者区域多行匹配', 'multi_from' => '来源区域多行匹配', 'multi_intro' => '简介区域多行匹配', 'enter' => 'UNIX格式设置', 'enter_link' => '连接区域UNIX格式', 'enter_title' => '标题区域UNIX格式', 'enter_body' => '内容区域UNIX格式', 'enter_author' => '作者区域UNIX格式', 'enter_from' => '来源区域UNIX格式', 'enter_intro' => '简介区域UNIX格式', 'filter_list' => '过滤器列表', 'filter' => '过滤器', 'filter_name' => '过滤器名字', 'filter_area' => '过滤器规则', 'filter_multi' => '过滤器规则多行匹配', 'filter_enter' => '过滤器规则UNIX格式');
	foreach ($CNTag as $k => $v)
	{
		$pattern[] = '/<' . $v . '>(.*?)<\\/' . $v . '>/is';
		$replace[] = '<' . $k . '>\\1</' . $k . '>';
	}

	if (!$_GET['setp'])
	{
		$tp->set_templatefile ('templates/rules_import.html');
		$moduleTemplate = $tp->result ();
		$moduleTitle = '导入采集器配置';
	}
	else
	{
		if ($_GET['setp'] == 'info')
		{
			$uploadDir = './tmp/';
			$fileExtension = strtolower (substr (strrchr ($_FILES['configFile']['name'], '.'), 1));
			$uploadFile = $uploadDir . $_FILES['configFile']['name'];
			if ($fileExtension != 'cfg')
			{
				error ('只能导入扩展名为"cfg"的配置文件!');
			}

			if (move_uploaded_file ($_FILES['configFile']['tmp_name'], $uploadFile))
			{
				$fileHandle = @fopen ($uploadFile, 'rb');
				if (!$fileHandle)
				{
					error ('找不到配置导入文件错误');
				}

				$content = fread ($fileHandle, filesize ($uploadFile));
				fclose ($fileHandle);
				$content = preg_replace ($pattern, $replace, $content);
				$NX = new NEAT_XML ();
				$NX->parse_document ($content);
				$newArray = $NX->xml_array['nc_rules_config']['information'];
				@unlink ($uploadFile);
				$lowLimitVersion = implode ('', explode ('.', NEAT_COLLECTOR_RULES_OLD_VERSION));
				$configVersion = implode ('', explode ('.', $newArray['version']));
				if ($configVersion < $lowLimitVersion)
				{
					error ('配置文件的版本和程序版本不符');
				}

				$newArray['author'] = nl2br ($newArray['author']);
				$newArray['readme'] = nl2br ($newArray['readme']);
				$newArray['contact'] = nl2br ($newArray['contact']);
				$tp->set_templatefile ('templates/rules_import_info.html');
				$tp->assign ($newArray);
				$tp->assign ('contents', base64_encode ($content));
				$moduleTemplate = $tp->result ();
				$moduleTitle = '导入采集器配置';
			}
			else
			{
				error ('配置文件上传失败,请检查配置和目录设置');
			}
		}
		else
		{
			$_POST['ruleName'] = trim ($_POST['ruleName']);
			if (!$_POST['ruleName'])
			{
				error ('请输入采集器的名称');
			}

			$content = base64_decode ($_POST['contents']);
			$NX = new NEAT_XML ();
			$NX->parse_document ($content);
			$newArray = $NX->xml_array['nc_rules_config'];
			if (!intval ($newArray['http']['method']))
			{
				$newArray['http']['method'] = 1;
			}

			$NBS = new NEATBulidSql (TB_RULES);
			$rulesFids['id'] = '';
			$rulesFids['name'] = addslashes ($_POST['ruleName']);
			$rulesFids['index_type'] = addslashes (trim ($newArray['index']['index_type']));
			$rulesFids['url'] = addslashes (trim (encodestring ($newArray['index']['index_url'])));
			$rulesFids['page_start'] = intval ($newArray['index']['page_start']);
			$rulesFids['page_end'] = intval ($newArray['index']['page_end']);
			$rulesFids['link_replace'] = addslashes ($newArray['index']['link_replace']);
			$rulesFids['method'] = intval ($newArray['http']['method']);
			$rulesFids['posts'] = addslashes ($newArray['http']['posts']);
			$rulesFids['cookies'] = addslashes ($newArray['http']['cookies']);
			$rulesFids['area_link'] = addslashes (encodestring ($newArray['area']['area_link']));
			$rulesFids['area_title'] = addslashes (encodestring ($newArray['area']['area_title']));
			$rulesFids['area_body'] = addslashes (encodestring ($newArray['area']['area_body']));
			$rulesFids['area_body_page'] = addslashes (encodestring ($newArray['page']['page_area']));
			$rulesFids['area_body_page_link'] = addslashes (encodestring ($newArray['page']['page_next']));
			$rulesFids['area_author'] = addslashes (encodestring ($newArray['area']['area_author']));
			$rulesFids['area_from'] = addslashes (encodestring ($newArray['area']['area_from']));
			$rulesFids['area_intro'] = addslashes (encodestring ($newArray['area']['area_intro']));
			$rulesFids['multi_link'] = intval ($newArray['multi']['multi_link']);
			$rulesFids['multi_title'] = intval ($newArray['multi']['multi_title']);
			$rulesFids['multi_body'] = intval ($newArray['multi']['multi_body']);
			$rulesFids['multi_body_page'] = intval ($newArray['page']['page_multi_page']);
			$rulesFids['multi_body_page_link'] = intval ($newArray['page']['page_multi_next']);
			$rulesFids['multi_author'] = intval ($newArray['multi']['multi_author']);
			$rulesFids['multi_from'] = intval ($newArray['multi']['multi_from']);
			$rulesFids['multi_intro'] = intval ($newArray['multi']['multi_intro']);
			$rulesFids['enter_link'] = intval ($newArray['enter']['enter_link']);
			$rulesFids['enter_title'] = intval ($newArray['enter']['enter_title']);
			$rulesFids['enter_body'] = intval ($newArray['enter']['enter_body']);
			$rulesFids['enter_body_page'] = intval ($newArray['page']['page_enter_page']);
			$rulesFids['enter_body_page_link'] = intval ($newArray['page']['page_enter_next']);
			$rulesFids['enter_author'] = intval ($newArray['enter']['enter_author']);
			$rulesFids['enter_from'] = intval ($newArray['enter']['enter_from']);
			$rulesFids['enter_intro'] = intval ($newArray['enter']['enter_intro']);
			$rulesFids['link_num'] = 0;
			$rulesFids['import_num'] = 0;
			$rulesFids['date'] = strtotime (date ('Y-m-d'));
			$rulesFids['body_page_type'] = intval ($newArray['page']['page_type']);
			$sql = $NBS->add ($rulesFids);
			$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
			$db->query ($sql);
			$NBS->setTable (TB_FILTER);
			$ruleID = $db->lastid ();
			if ($newArray['filter_list']['filter'])
			{
				if ($newArray['filter_list']['filter'][0])
				{
					foreach ($newArray['filter_list']['filter'] as $val)
					{
						$tmpFilterFids['filter_multi'] = intval ($val['filter_multi']);
						$tmpFilterFids['filter_enter'] = intval ($val['filter_enter']);
						$tmpFilterFids['filter_rule'] = addslashes (encodestring ($val['filter_area']));
						$tmpFilterFids['filter_name'] = addslashes ($val['filter_name']);
						$tmpFilterFids['rule_id'] = $ruleID;
						$sql = $NBS->add ($tmpFilterFids);
						$db->query ($sql);
					}
				}
				else
				{
					$tmpFilterFids['filter_multi'] = intval ($newArray['filter_list']['filter']['filter_multi']);
					$tmpFilterFids['filter_enter'] = intval ($newArray['filter_list']['filter']['filter_enter']);
					$tmpFilterFids['filter_rule'] = addslashes (encodestring ($newArray['filter_list']['filter']['filter_area']));
					$tmpFilterFids['filter_name'] = addslashes ($newArray['filter_list']['filter']['filter_name']);
					$tmpFilterFids['rule_id'] = $ruleID;
					$sql = $NBS->add ($tmpFilterFids);
					$db->query ($sql);
				}
			}

			showloading ('?module=listRules', '导入采集器配置', '采集器: ' . $_POST['ruleName'] . ' 导入成功,现在返回采集器列表.');
			$tpShowBody = false;
		}
	}

?>
