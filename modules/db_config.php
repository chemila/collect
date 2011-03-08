<?

	if (!$_GET['setp'])
	{
		session_start ();
		session_destroy ();
		$tp->set_templatefile ('templates/db_cfg_setting.html');
		$tp->assign ('post_to', 'index.php?module=configDB&setp=testConnect');
		$tp->assign ('mysql_checked', 'checked');
		$tp->assign ('ado_access_checked', '');
		$tp->assign ('mysql_display', 'block');
		$tp->assign ('ado_access_display', 'none');
		$tp->assign ('ado_mssql_display', 'none');
		$moduleTemplate = $tp->result ();
		$moduleTitle = '数据库连接设置';
	}
	else
	{
		session_start ();
		if ($_POST['type'])
		{
			$databaseType = $_POST['type'];
		}
		else
		{
			$databaseType = $_GET['type'];
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

			showloading ('index.php?module=configDB&type=' . $_POST['type'] . '&setp=listTables', '连接成功...', '数据库连接成功,现在转向下一步.', 1);
			$tpShowBody = false;
		}
		else
		{
			if ($_GET['setp'] == 'listTables')
			{
				$tableList = $db2db->getTables ($_SESSION['TARGET_DB_NAME']);
				$list['list'] = $tableList;
				$tp->set_templatefile ('templates/db_cfg_list_tables.html');
				$tp->assign ($list);
				$tp->assign ('post_to', 'index.php?module=configDB&type=' . $_GET['type'] . '&setp=listFields');
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

					$i = 0;
					foreach ($_POST['table'] as $k => $v)
					{
						$list['table_list'][$i]['article_table'] = $v;
						$fieldList = $db2db->getFields ($v);
						$list['table_list'][$i]['list'] = $fieldList;
						++$i;
					}

					$tp->set_templatefile ('templates/db_cfg_list_fields.html');
					$tp->assign ($list);
					$tp->assign ('post_to', 'index.php?module=configDB&type=' . $_GET['type'] . '&setp=listInput');
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

						$TARGET_DB_ARTICLE = $_GET['table'];
						$i = 0;
						foreach ($_POST['fields'] as $k => $v)
						{
							$I = 0;
							foreach ($v as $tk => $tv)
							{
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
						$tp->assign ('post_to', 'index.php?module=configDB&type=' . $_GET['type'] . '&setp=hypotaxis');
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
							while ($rs->next_record ())
							{
								$list['option'][$i]['rulesID'] = $rs->get ('id');
								$list['option'][$i]['rulesName'] = $rs->get ('name');
								++$i;
							}

							$db->disconnect ();
							$tp->set_templatefile ('templates/db_cfg_list_hypotaxis.html');
							$tp->assign ($list);
							$tp->assign ('post_to', 'index.php?module=configDB&type=' . $_GET['type'] . '&setp=saveConfig');
							$tp->assign ('js_i', 1);
							$moduleTemplate = $tp->result ();
							$moduleTitle = '系统参数设置';
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

								$name = trim ($_POST['configName']);
								$time = strtotime (date ('Y-m-d H:i:s'));
								$NBS = new NEATBulidSql (TB_DB2DB);
								$configFids['id'] = '';
								$configFids['db_type'] = trim ($_GET['type']);
								$configFids['rules'] = @implode (',', $_POST['rulesID']);
								$configFids['name'] = $name;
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
								$configFids['date'] = $time;
								$sql = $NBS->add ($configFids);
								$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
								$db->query ($sql);
								$db->disconnect ();
								showloading ('index.php?module=listDB', '添加成功', '导入配置: ' . $name . ' 添加成功,现在返回采集器列表.');
								$tpShowBody = false;
							}
						}
					}
				}
			}
		}
	}

?>
