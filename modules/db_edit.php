<?

	session_start ();
	if (!$_GET['ID'])
	{
		error ('导入配置编号不能为空');
	}

	if (!$_GET['setp'])
	{
		$sql = 'SELECT * ';
		$sql .= 'FROM ' . TB_DB2DB . ' ';
		$sql .= 'WHERE id = ' . intval ($_GET['ID']);
		$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$rs = $db->query ($sql);
		$rs->next_record ();
		$HOST = $rs->get ('host');
		$USER = $rs->get ('user');
		$PASS = $rs->get ('password');
		$NAME = $rs->get ('db_name');
		$TYPE = $rs->get ('db_type');
		$_SESSION['configName'] = $rs->get ('name');
		$_SESSION['fieldArray'] = $rs->get ('field_list');
		$_SESSION['valueArray'] = $rs->get ('value_list');
		$_SESSION['countFieldsArray'] = $rs->get ('recount_fields_list');
		$_SESSION['countFieldsValueArray'] = $rs->get ('recount_fields_value_list');
		$_SESSION['countRulesArray'] = $rs->get ('recount_rules_list');
		$_SESSION['countRulesValueArray'] = $rs->get ('recount_rules_value_list');
		$_SESSION['article_table'] = $rs->get ('article_table');
		$_SESSION['ruleID'] = $rs->get ('rules');
		$tp->set_templatefile ('templates/db_cfg_setting.html');
		$tp->assign ('post_to', 'index.php?module=editDB&ID=' . $_GET['ID'] . '&setp=testConnect');
		switch ($TYPE)
		{
			case 'mysql':
			{
				$tp->assign ('mysql_checked', 'checked');
				$tp->assign ('mysql_display', 'block');
				$tp->assign ('ado_access_display', 'none');
				$tp->assign ('ado_mssql_display', 'none');
				$tp->assign ('MYSQL_TARGET_DB_HOST', $HOST);
				$tp->assign ('MYSQL_TARGET_DB_USER', $USER);
				$tp->assign ('MYSQL_TARGET_DB_PASS', $PASS);
				$tp->assign ('MYSQL_TARGET_DB_NAME', $NAME);
				break;
			}

			case 'ado_access':
			{
				$tp->assign ('ado_access_checked', 'checked');
				$tp->assign ('ado_access_display', 'block');
				$tp->assign ('ado_mssql_display', 'none');
				$tp->assign ('mysql_display', 'none');
				$tp->assign ('ADO_ACCESS_TARGET_DB_HOST', $HOST);
				$tp->assign ('ADO_ACCESS_TARGET_DB_USER', $USER);
				$tp->assign ('ADO_ACCESS_TARGET_DB_PASS', $PASS);
				$tp->assign ('ADO_ACCESS_TARGET_DB_NAME', $NAME);
				break;
			}

			case 'ado_mssql':
			{
				$tp->assign ('ado_mssql_checked', 'checked');
				$tp->assign ('ado_mssql_display', 'block');
				$tp->assign ('ado_access_display', 'none');
				$tp->assign ('mysql_display', 'none');
				$tp->assign ('ADO_MSSQL_TARGET_DB_HOST', $HOST);
				$tp->assign ('ADO_MSSQL_TARGET_DB_USER', $USER);
				$tp->assign ('ADO_MSSQL_TARGET_DB_PASS', $PASS);
				$tp->assign ('ADO_MSSQL_TARGET_DB_NAME', $NAME);
			}
		}

		$moduleTemplate = $tp->result ();
		$moduleTitle = '数据库连接设置';
	}
	else
	{
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
		if ($_GET['setp'] == 'testConnect')
		{
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
					error ('目标数据库服务器连接失败,请检查目标数据库的设置.');
					break;
				}

				case 2:
				{
					error ('找不到目标数据库的“' . $TARGET_DB_NAME . '”,请检查目标数据库的设置.');
					break;
				}

				case 3:
				{
					$db2db->setSession ();
				}
			}

			showloading ('index.php?module=editDB&ID=' . $_GET['ID'] . '&type=' . $_POST['type'] . '&setp=listTables', '连接成功...', '数据库连接成功,现在转向下一步.', 1);
			$tpShowBody = false;
		}
		else
		{
			if ($_GET['setp'] == 'listTables')
			{
				$sql = 'SELECT * ';
				$sql .= 'FROM ' . TB_DB2DB . ' ';
				$sql .= 'WHERE id = ' . $_GET['ID'];
				$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
				$rs = $db->query ($sql);
				$rs->next_record ();
				$dbTableList = explode ('|', $rs->get ('article_table'));
				$tableList = $db2db->getTables ($_SESSION['TARGET_DB_NAME']);
				$list['list'] = $tableList;
				foreach ($tableList as $k => $v)
				{
					if (in_array ($v['name'], $dbTableList))
					{
						$list['list'][$k]['checked'] = 'checked';
						continue;
					}
				}

				$tp->set_templatefile ('templates/db_cfg_list_tables.html');
				$tp->assign ($list);
				$tp->assign ('post_to', 'index.php?module=editDB&ID=' . $_GET['ID'] . '&type=' . $_GET['type'] . '&setp=listFields');
				$tp->assign ('database', $_SESSION['TARGET_DB_NAME']);
				$moduleTemplate = $tp->result ();
				$moduleTitle = '数据库数据表列表';
			}
			else
			{
				if ($_GET['setp'] == 'listFields')
				{
					if (!$_POST['table'])
					{
						error ('请您选择一个数据表');
					}

					$sql = 'SELECT * ';
					$sql .= 'FROM ' . TB_DB2DB . ' ';
					$sql .= 'WHERE id = ' . $_GET['ID'];
					$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					$rs = $db->query ($sql);
					$rs->next_record ();
					$dbTableList = explode ('|', $rs->get ('article_table'));
					$dbFieldListTemp = explode ('#', $rs->get ('field_list'));
					foreach ($dbFieldListTemp as $k => $v)
					{
						$keyName = $dbTableList[$k];
						$dbFieldList[$keyName] = explode ('|', $v);
					}

					$i = 0;
					foreach ($_POST['table'] as $k => $v)
					{
						$list['table_list'][$i]['article_table'] = $v;
						$fieldList = $db2db->getFields ($v);
						$list['table_list'][$i]['list'] = $fieldList;
						++$i;
					}

					foreach ($list['table_list'] as $k => $v)
					{
						foreach ($list['table_list'][$k]['list'] as $tk => $tv)
						{
							if (@in_array ($list['table_list'][$k]['list'][$tk]['article_table_id'], $dbTableList))
							{
								$tableName = $list['table_list'][$k]['list'][$tk]['article_table_id'];
								if (@in_array ($list['table_list'][$k]['list'][$tk]['name'], $dbFieldList[$tableName]))
								{
									$list['table_list'][$k]['list'][$tk]['checked'] = 'checked';
									continue;
								}

								continue;
							}
						}
					}

					$tp->set_templatefile ('templates/db_cfg_list_fields.html');
					$tp->assign ($list);
					$tp->assign ('post_to', 'index.php?module=editDB&&ID=' . $_GET['ID'] . '&type=' . $_GET['type'] . '&setp=listInput&table=' . $TARGET_DB_ARTICLE);
					$moduleTemplate = $tp->result ();
					$moduleTitle = '数据库字段列表';
				}
				else
				{
					if ($_GET['setp'] == 'listInput')
					{
						if (empty ($_POST['fields']))
						{
							error ('请您至少选择一个数据字段!');
						}

						$sql = 'SELECT * ';
						$sql .= 'FROM ' . TB_DB2DB . ' ';
						$sql .= 'WHERE id = ' . $_GET['ID'];
						$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
						$rs = $db->query ($sql);
						$rs->next_record ();
						$dbTableList = explode ('|', $rs->get ('article_table'));
						$dbFieldListTemp = explode ('#', $rs->get ('field_list'));
						foreach ($dbFieldListTemp as $k => $v)
						{
							$keyName = $dbTableList[$k];
							$dbFieldList[$keyName] = explode ('|', $v);
						}

						$dbFieldValueListTemp = explode ('#', $rs->get ('value_list'));
						foreach ($dbFieldValueListTemp as $k => $v)
						{
							$keyName = $dbTableList[$k];
							$temp = explode ('|', $v);
							$tableName = $dbFieldList[$keyName];
							foreach ($temp as $tk => $tv)
							{
								$thisKey = $tableName[$tk];
								$dbFieldValueList[$keyName][$thisKey] = $tv;
							}
						}

						$i = 0;
						foreach ($_POST['fields'] as $k => $v)
						{
							$I = 0;
							foreach ($v as $tk => $tv)
							{
								$fieldValue = $dbFieldValueList[$k][$tv];
								if (strlen ($fieldValue))
								{
									$list['field_list'][$i]['list'][$I]['value'] = $fieldValue;
								}

								$list['field_list'][$i]['list'][$I]['tag_value'] = $k . '.' . $tv;
								$list['field_list'][$i]['list'][$I]['article_table_id'] = $k;
								$list['field_list'][$i]['list'][$I]['order'] = $I + 1;
								$list['field_list'][$i]['list'][$I]['fields'] = $tv;
								++$I;
							}

							$list['field_list'][$i]['article_table'] = $k;
							++$i;
						}

						$tp->set_templatefile ('templates/db_cfg_list_input.html');
						$tp->assign ($list);
						$tp->assign ('post_to', 'index.php?module=editDB&ID=' . $_GET['ID'] . '&type=' . $_GET['type'] . '&setp=hypotaxis&&table=' . $TARGET_DB_ARTICLE);
						$tp->assign ('article_table', $TARGET_DB_ARTICLE);
						$moduleTemplate = $tp->result ();
						$moduleTitle = '建立对应关系';
					}
					else
					{
						if ($_GET['setp'] == 'hypotaxis')
						{
							$i = 0;
							foreach ($_POST['hypotaxis'] as $k => $v)
							{
								$I = 0;
								foreach ($v as $tk => $tv)
								{
									$list['field_list'][$i]['list'][$I]['article_table_id'] = $k;
									$list['field_list'][$i]['list'][$I]['order'] = $I + 1;
									$list['field_list'][$i]['list'][$I]['fields'] = $tk;
									$list['field_list'][$i]['list'][$I]['value'] = $tv;
									++$I;
								}

								$list['field_list'][$i]['article_table'] = $k;
								++$i;
							}

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

							$countFieldsList = explode ('|', $_SESSION['countFieldsArray']);
							$countFieldsValueList = explode ('|', $_SESSION['countFieldsValueArray']);
							$countRulesList = explode ('|', $_SESSION['countRulesArray']);
							$countRulesValueList = explode ('|', $_SESSION['countRulesValueArray']);
							$i = 0;
							foreach ($countFieldsList as $k => $v)
							{
								$list['count'][$i]['count_id'] = $i + 1;
								$list['count'][$i]['count_fields'] = $v;
								$list['count'][$i]['count_fields_value'] = $countFieldsValueList[$k];
								$list['count'][$i]['count_rules'] = $countRulesList[$k];
								$list['count'][$i]['count_rules_value'] = $countRulesValueList[$k];
								++$i;
							}

							$tp->set_templatefile ('templates/db_cfg_list_hypotaxis.html');
							$tp->assign ($list);
							$tp->assign ('post_to', 'index.php?module=editDB&ID=' . $_GET['ID'] . '&type=' . $_GET['type'] . '&setp=saveConfig&table=' . $TARGET_DB_ARTICLE);
							$tp->assign ('configName', $_SESSION['configName']);
							$tp->assign ('js_i', count ($countFieldsList) + 1);
							$moduleTemplate = $tp->result ();
							$moduleTitle = '查看对应关系';
						}
						else
						{
							if ($_GET['setp'] == 'saveConfig')
							{
								if (!trim ($_POST['configName']))
								{
									error ('请输入导出配置名称');
								}

								$tableNum = count ($_POST['hypotaxis']);
								$i = 1;
								foreach ($_POST['hypotaxis'] as $k => $v)
								{
									$tableList .= $k;
									$fieldNum = count ($_POST['hypotaxis'][$k]);
									$I = 1;
									foreach ($v as $tk => $tv)
									{
										$fieldList .= $tk;
										$dataList .= $tv;
										if ($I < $fieldNum)
										{
											$fieldList .= '|';
											$dataList .= '|';
										}

										++$I;
									}

									if ($i < $tableNum)
									{
										$tableList .= '|';
										$fieldList .= '#';
										$dataList .= '#';
									}

									++$i;
								}

								if (is_array ($_POST['count_fields']))
								{
									$tableListArray = $db2db->getTables ($_SESSION['TARGET_DB_NAME']);
									$num = 0;
									foreach ($_POST['count_fields'] as $k => $v)
									{
										if ($v)
										{
											++$num;
											continue;
										}
									}

									$i = 1;
									foreach ($_POST['count_fields'] as $k => $v)
									{
										if (($v AND $_POST['count_fields_value'][$k]))
										{
											$tableExist = false;
											foreach ($tableListArray as $tk => $tv)
											{
												if ($tableListArray[$tk]['name'] == $v)
												{
													$tableExist = true;
													continue;
												}
											}

											if (!$tableExist)
											{
												error ($v . '表不存在于数据库' . $_SESSION['TARGET_DB_NAME'] . '中.');
											}

											$fieldListArray = $db2db->getFields ($v);
											$fieldExist = false;
											foreach ($fieldListArray as $fk => $fv)
											{
												if ($fieldListArray[$fk]['name'] == $_POST['count_fields_value'][$k])
												{
													$fieldExist = true;
													continue;
												}
											}

											if (!$fieldExist)
											{
												error ('字段 ' . $_POST['count_fields_value'][$k] . '不存在于数据表' . $v . '中.');
											}

											$countFieldsList .= $v;
											$countFieldsValueList .= $_POST['count_fields_value'][$k];
											$countRulesList .= $_POST['count_rules'][$k];
											$countRulesValueList .= $_POST['count_rules_value'][$k];
											if ($i < $num)
											{
												$countFieldsList .= '|';
												$countFieldsValueList .= '|';
												$countRulesList .= '|';
												$countRulesValueList .= '|';
											}

											++$i;
											continue;
										}
									}
								}

								$name = $_POST['configName'];
								$time = strtotime (date ('Y-m-d H:i:s'));
								$NBS = new NEATBulidSql (TB_DB2DB);
								$configFids['name'] = $name;
								$configFids['db_type'] = trim ($_GET['type']);
								$configFids['rules'] = @implode (',', $_POST['rulesID']);
								$configFids['host'] = $_SESSION['TARGET_DB_HOST'];
								$configFids['user'] = $_SESSION['TARGET_DB_USER'];
								$configFids['password'] = $_SESSION['TARGET_DB_PASS'];
								$configFids['db_name'] = $_SESSION['TARGET_DB_NAME'];
								$configFids['article_table'] = $tableList;
								$configFids['field_list'] = $fieldList;
								$configFids['value_list'] = $dataList;
								$configFids['recount_fields_list'] = $countFieldsList;
								$configFids['recount_fields_value_list'] = $countFieldsValueList;
								$configFids['recount_rules_list'] = $countRulesList;
								$configFids['recount_rules_value_list'] = $countRulesValueList;
								$configCondition['id'] = intval ($_GET['ID']);
								$sql = $NBS->update ($configFids, $configCondition);
								$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
								$db->query ($sql);
								$db->disconnect ();
								session_destroy ();
								showloading ('index.php?module=listDB', '修改成功', '导入配置: ' . $name . ' 修改成功,现在返回配置列表.');
								$tpShowBody = false;
							}
						}
					}
				}
			}
		}
	}

?>
