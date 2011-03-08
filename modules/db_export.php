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

	$CNTag = array ('nc_dbexport_config' => '易采_采集器数据库配置', 'information' => '配置信息', 'version' => '软件版本', 'config_name' => '配置名称', 'author' => '作者名字', 'contact' => '联系方式', 'readme' => '导出说明', 'date' => '导出时间', 'access' => '数据库设置', 'access_type' => '数据库类型', 'access_host' => '数据库地址', 'access_user' => '数据库用户', 'access_pass' => '数据库密码', 'access_dbname' => '数据库名称', 'otherdb' => '导出数据库设置', 'otherdb_table' => '导出数据库对应表', 'otherdb_fields' => '导出数据库对应字段', 'otherdb_rules' => '导出数据库对应关系');

	if (!$_GET['ID'])
	{
		error ('导出的配置编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('导出的配置编号只能是数字!');
	}

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_DB2DB . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$rs->next_record ();
	if (!$rs)
	{
		error ('找不到编号为' . $_GET['ID'] . '的数据库导出配置');
	}

	if (!$_GET['action'])
	{
		$tp->set_templatefile ('templates/db_cfg_export.html');
		$tp->assign ('id', $rs->get ('id'));
		$tp->assign ('config_name', $rs->get ('name'));
		$moduleTemplate = $tp->result ();
		$moduleTitle = '数据库导出配置';
		$db->disconnect ();
	}
	else
	{
		$configName = trim ($_POST['configname']);
		if (!$_POST['config_name'])
		{
			error ('请输入配置名称');
		}

		$sourceArray = $rs->getarray ();
		$cfgArray['information']['version'] = NEAT_COLLECTOR_VERSION;
		$cfgArray['information']['config_name'] = trim ($_POST['config_name']);
		$cfgArray['information']['author'] = trim ($_POST['author']);
		$cfgArray['information']['contact'] = trim ($_POST['contact']);
		$cfgArray['information']['readme'] = trim ($_POST['readme']);
		$cfgArray['information']['date'] = date ('Y-m-d H:i:s');
		$cfgArray['access']['access_type'] = $rs->get ('db_type');
		$cfgArray['otherdb']['otherdb_table'] = $rs->get ('article_table');
		$cfgArray['otherdb']['otherdb_fields'] = $rs->get ('field_list');
		$cfgArray['otherdb']['otherdb_rules'] = $rs->get ('value_list');
		$allArrry['nc_dbexport_config'] = $cfgArray;
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
		header ('Content-Disposition: inline;filename= ' . trim ($fileName) . '.dbcfg');
		header ('Content-Type: cfg');
		echo $XMLContent;
		exit ();
	}

?>
