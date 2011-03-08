<?

	include_once 'includes/class/basic/NEATXML.class.php';
	$CNTag = array ('nc_dbexport_config' => '易采_采集器数据库配置', 'information' => '配置信息', 'version' => '软件版本', 'config_name' => '配置名称', 'author' => '作者名字', 'contact' => '联系方式', 'readme' => '导出说明', 'date' => '导出时间', 'access' => '数据库设置', 'access_type' => '数据库类型', 'access_host' => '数据库地址', 'access_user' => '数据库用户', 'access_pass' => '数据库密码', 'access_dbname' => '数据库名称', 'otherdb' => '导出数据库设置', 'otherdb_table' => '导出数据库对应表', 'otherdb_fields' => '导出数据库对应字段', 'otherdb_rules' => '导出数据库对应关系');
	foreach ($CNTag as $k => $v)
	{
		$pattern[] = '/<' . $v . '>(.*?)<\\/' . $v . '>/is';
		$replace[] = '<' . $k . '>\\1</' . $k . '>';
	}

	if (!$_GET['setp'])
	{
		session_start ();
		session_destroy ();
		$tp->set_templatefile ('templates/db_import.html');
		$moduleTemplate = $tp->result ();
		$moduleTitle = '导入数据库配置';
	}
	else
	{
		if ($_GET['setp'] == 'info')
		{
			session_start ();
			session_destroy ();
			$uploadDir = './tmp/';
			$fileExtension = strtolower (substr (strrchr ($_FILES['configFile']['name'], '.'), 1));
			$uploadFile = $uploadDir . $_FILES['configFile']['name'];
			if ($fileExtension != 'dbcfg')
			{
				error ('只能导入扩展名为"dbcfg"的配置文件!');
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
				$newArray = $NX->xml_array['nc_dbexport_config']['information'];
				$dbArray = $NX->xml_array['nc_dbexport_config']['access'];
				@unlink ($uploadFile);
				$lowLimitVersion = implode ('', explode ('.', NEAT_COLLECTOR_RULES_OLD_VERSION));
				$configVersion = implode ('', explode ('.', $newArray['version']));
				if ($configVersion < $lowLimitVersion)
				{
					error ('配置文件的版本和程序版本不符');
				}

				$dbInfo['post_to'] = 'index.php?module=importDBRules&setp=testConnect';
				switch ($dbArray['access_type'])
				{
					case 'mysql':
					{
						$dbInfo['mysql_checked'] = 'checked';
						$dbInfo['mysql_display'] = 'block';
						$dbInfo['ado_access_display'] = 'none';
						break;
					}

					case 'ado_access':
					{
						$dbInfo['ado_access_checked'] = 'checked';
						$dbInfo['ado_access_display'] = 'block';
						$dbInfo['mysql_display'] = 'none';
						break;
					}

					default:
					{
					}
				}

				$tp->set_templatefile ('templates/db_cfg_setting.html');
				$tp->assign ($dbInfo);
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
			if ($_GET['setp'] == 'testConnect')
			{
				session_start ();
				if ($_POST['type'])
				{
					$databaseType = trim ($_POST['type']);
				}
				else
				{
					$databaseType = trim ($_GET['type']);
				}

				include_once 'adodb/adodb.inc.php';
				$adodb = adonewconnection ($databaseType);
				$objName = 'NEAT_DB2DB_' . strtoupper ($databaseType);
				include_once 'includes/class/extra/NEATDB2DB.' . $databaseType . '.class.php';
				$db2db = new $objName ();
				$db2db->setADODB ($adodb);
				$_SESSION['content'] = $_POST['contents'];
				$dbStatus = $db2db->testConnect ();
				if (trim ($_POST['MYSQL_TARGET_DB_NAME']))
				{
					$TARGET_DB_NAME = trim ($_POST['MYSQL_TARGET_DB_NAME']);
				}
				else
				{
					if (trim ($_POST['ADO_ACCESS_TARGET_DB_HOST']))
					{
						$TARGET_DB_NAME = trim ($_POST['ADO_ACCESS_TARGET_DB_HOST']);
					}
					else
					{
						if (trim ($_POST['ADO_MSSQL_TARGET_DB_NAME']))
						{
							$TARGET_DB_NAME = trim ($_POST['ADO_MSSQL_TARGET_DB_NAME']);
						}
					}
				}

				switch ($dbStatus)
				{
					case 1:
					{
						error ('目标数据库服务器连接失败,请检查目标数据库的设置.', '-2');
						break;
					}

					case 2:
					{
						error ('找不到目标数据库的“' . $TARGET_DB_NAME . '”,请检查目标数据库的设置.', '-2');
						break;
					}

					case 3:
					{
						$db2db->setSession ();
					}
				}

				$table = $db2db->getTables ($TARGET_DB_NAME);
				foreach ($table as $kk => $vv)
				{
					$dbTable[] = $vv['name'];
				}

				$contents = base64_decode ($_POST['contents']);
				$NX = new NEAT_XML ();
				$NX->parse_document ($contents);
				$newArray = $NX->xml_array['nc_dbexport_config']['otherdb'];
				$otherdb = explode ('|', $newArray['otherdb_table']);
				foreach ($otherdb as $kk => $vv)
				{
					if (!in_array ($vv, $dbTable))
					{
						error ('数据库存在,但没有与导入配置相同的表,\\n\\n请检查是否连接错误的数据库或者表的前缀是否与标配相同.', '-2');
						continue;
					}
				}

				showloading ('index.php?module=importDBRules&setp=importSetting', '连接成功...', '数据库连接成功,现在转向下一步.', 1);
				$tpShowBody = false;
			}
			else
			{
				if ($_GET['setp'] == 'importSetting')
				{
					session_start ();
					$contents = base64_decode ($_SESSION['content']);
					$NX = new NEAT_XML ();
					$NX->parse_document ($contents);
					$newArray = $NX->xml_array['nc_dbexport_config']['information'];
					$lowLimitVersion = implode ('', explode ('.', NEAT_COLLECTOR_RULES_OLD_VERSION));
					$configVersion = implode ('', explode ('.', $newArray['version']));
					if ($configVersion < $lowLimitVersion)
					{
						error ('配置文件的版本和程序版本不符', '-3');
					}

					$newArray['author'] = nl2br ($newArray['author']);
					$newArray['readme'] = nl2br ($newArray['readme']);
					$newArray['contact'] = nl2br ($newArray['contact']);
					$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					$sql = 'SELECT id, name ';
					$sql .= 'FROM ' . TB_RULES;
					$rs = $db->query ($sql);
					$i = 0;
					$rulesArray = explode (',', $_SESSION['ruleID']);
					while ($rs->next_record ())
					{
						$list['option'][$i]['rulesID'] = $rs->get ('id');
						$list['option'][$i]['rulesName'] = $rs->get ('name');
						(in_array ($rs->get ('id'), $rulesArray) ? $list['option'][$i]['selected'] = ' selected' : $list['option'][$i]['selected'] = '');
						++$i;
					}

					$tp->set_templatefile ('templates/db_import_info.html');
					$tp->assign ($list);
					$tp->assign ($newArray);
					$moduleTemplate = $tp->result ();
					$moduleTitle = '导入采集器配置';
				}
				else
				{
					session_start ();
					$_POST['configName'] = trim ($_POST['configName']);
					if (!$_POST['configName'])
					{
						error ('请输入数据库配置的名称');
					}

					$contents = base64_decode ($_SESSION['content']);
					$NX = new NEAT_XML ();
					$NX->parse_document ($contents);
					$newArray = $NX->xml_array['nc_dbexport_config'];
					$time = strtotime (date ('Y-m-d H:i:s'));
					$NBS = new NEATBulidSql (TB_DB2DB);
					$configFids['name'] = $_POST['configName'];
					$configFids['db_type'] = addslashes (trim ($newArray['access']['access_type']));
					$configFids['rules'] = @implode (',', $_POST['rulesID']);
					$configFids['host'] = addslashes ($_SESSION['TARGET_DB_HOST']);
					$configFids['user'] = addslashes ($_SESSION['TARGET_DB_USER']);
					$configFids['password'] = addslashes ($_SESSION['TARGET_DB_PASS']);
					$configFids['db_name'] = addslashes ($_SESSION['TARGET_DB_NAME']);
					$configFids['article_table'] = addslashes (trim ($newArray['otherdb']['otherdb_table']));
					$configFids['field_list'] = addslashes (trim ($newArray['otherdb']['otherdb_fields']));
					$configFids['value_list'] = addslashes (trim ($newArray['otherdb']['otherdb_rules']));
					$configFids['date'] = $time;
					$sql = $NBS->add ($configFids);
					$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					$db->query ($sql);
					$db->disconnect ();
					session_destroy ();
					showloading ('index.php?module=listDB', '导入成功', '导入配置: ' . $_POST['configName'] . ' 导入成功,现在返回配置列表.');
					$tpShowBody = false;
				}
			}
		}
	}

?>
