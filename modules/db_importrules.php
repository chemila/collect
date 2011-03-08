<?

	include_once 'includes/class/basic/NEATXML.class.php';
	$CNTag = array ('nc_dbexport_config' => '�ײ�_�ɼ������ݿ�����', 'information' => '������Ϣ', 'version' => '����汾', 'config_name' => '��������', 'author' => '��������', 'contact' => '��ϵ��ʽ', 'readme' => '����˵��', 'date' => '����ʱ��', 'access' => '���ݿ�����', 'access_type' => '���ݿ�����', 'access_host' => '���ݿ��ַ', 'access_user' => '���ݿ��û�', 'access_pass' => '���ݿ�����', 'access_dbname' => '���ݿ�����', 'otherdb' => '�������ݿ�����', 'otherdb_table' => '�������ݿ��Ӧ��', 'otherdb_fields' => '�������ݿ��Ӧ�ֶ�', 'otherdb_rules' => '�������ݿ��Ӧ��ϵ');
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
		$moduleTitle = '�������ݿ�����';
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
				error ('ֻ�ܵ�����չ��Ϊ"dbcfg"�������ļ�!');
			}

			if (move_uploaded_file ($_FILES['configFile']['tmp_name'], $uploadFile))
			{
				$fileHandle = @fopen ($uploadFile, 'rb');
				if (!$fileHandle)
				{
					error ('�Ҳ������õ����ļ�����');
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
					error ('�����ļ��İ汾�ͳ���汾����');
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
				$moduleTitle = '����ɼ�������';
			}
			else
			{
				error ('�����ļ��ϴ�ʧ��,�������ú�Ŀ¼����');
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
						error ('Ŀ�����ݿ����������ʧ��,����Ŀ�����ݿ������.', '-2');
						break;
					}

					case 2:
					{
						error ('�Ҳ���Ŀ�����ݿ�ġ�' . $TARGET_DB_NAME . '��,����Ŀ�����ݿ������.', '-2');
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
						error ('���ݿ����,��û���뵼��������ͬ�ı�,\\n\\n�����Ƿ����Ӵ�������ݿ���߱��ǰ׺�Ƿ��������ͬ.', '-2');
						continue;
					}
				}

				showloading ('index.php?module=importDBRules&setp=importSetting', '���ӳɹ�...', '���ݿ����ӳɹ�,����ת����һ��.', 1);
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
						error ('�����ļ��İ汾�ͳ���汾����', '-3');
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
					$moduleTitle = '����ɼ�������';
				}
				else
				{
					session_start ();
					$_POST['configName'] = trim ($_POST['configName']);
					if (!$_POST['configName'])
					{
						error ('���������ݿ����õ�����');
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
					showloading ('index.php?module=listDB', '����ɹ�', '��������: ' . $_POST['configName'] . ' ����ɹ�,���ڷ��������б�.');
					$tpShowBody = false;
				}
			}
		}
	}

?>
