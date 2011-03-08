<?


	function changeorder ($up, $down)
	{
		global $dbAllList;
		$upKey = array_keys ($dbAllList['orderList'], $up);
		$upKey = $upKey[0];
		$downKey = array_keys ($dbAllList['orderList'], $down);
		$downKey = $downKey[0];
		if ($downKey < $upKey)
		{
			$temp = $dbAllList['orderList'][$downKey];
			$dbAllList['orderList'][$downKey] = $up;
			$dbAllList['orderList'][$upKey] = $temp;
		}

	}

	function getrandom ($len = 4, $type = 0)
	{
		switch ($type)
		{
			case 1:
			{
				$stringRange = '0123456789';
				break;
			}

			case 2:
			{
				$stringRange = 'abcdefghijklmnopqrstuvwxyz';
				break;
			}

			case 3:
			{
				$stringRange = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			}

			case 4:
			{
				$stringRange = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			}

			default:
			{
				$stringRange = '0123456789';
			}
		}

		for ($i = 0; $i < $len; ++$i)
		{
			$seek = rand (0, strlen ($stringRange) - 1);
			$codeString .= $stringRange[$seek];
		}

		return $codeString;
	}

	include_once 'adodb/adodb.inc.php';
	if (!$_GET['ID'])
	{
		error ('导入配置编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('导入配置编号只能是数字!');
	}

	$sql = 'SELECT * ';
	$sql .= 'FROM ' . TB_DB2DB . ' ';
	$sql .= 'WHERE id = ' . $_GET['ID'];
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$rs = $db->query ($sql);
	$rs->next_record ();
	$HOST = $rs->get ('host');
	$USER = $rs->get ('user');
	$PASS = $rs->get ('password');
	$NAME = $rs->get ('db_name');
	$ruleID = $rs->get ('rules');
	$DB_TYPE = $rs->get ('db_type');
	$fieldsList = $rs->get ('field_list');
	$valuesList = $rs->get ('value_list');
	$fieldArray = explode ('||', $fieldsList);
	$valueArray = explode ('|', $valuesList);
	$recount_fieldsList = $rs->get ('recount_fields_list');
	$recount_fieldsValueList = $rs->get ('recount_fields_value_list');
	$recount_fieldsArray = explode ('|', $recount_fieldsList);
	$recount_fieldsValueArray = explode ('|', $recount_fieldsValueList);
	$recount_rulesList = $rs->get ('recount_rules_list');
	$recount_rulesValueList = $rs->get ('recount_rules_value_list');
	$recount_rulesArray = explode ('|', $recount_rulesList);
	$recount_rulesValueArray = explode ('|', $recount_rulesValueList);
	$dbTableList = explode ('|', $rs->get ('article_table'));
	$dbFieldListTemp = explode ('#', $rs->get ('field_list'));
	$dbFieldValueListTemp = explode ('#', $rs->get ('value_list'));
	foreach ($dbFieldListTemp as $k => $v)
	{
		$keyName = $dbTableList[$k];
		$dbAllList['orderList'][] = $keyName;
		$dbAllList['fieldList'][$keyName] = explode ('|', $v);
		$dbAllList['valueList'][$keyName] = explode ('|', $dbFieldValueListTemp[$k]);
		foreach ($dbAllList['fieldList'][$keyName] as $tk => $tv)
		{
			$dbAllList['checkList'][] = $keyName . '.' . $tv;
		}
	}

	foreach ($dbAllList['valueList'] as $k => $v)
	{
		$tableName = $k;
		foreach ($v as $tk => $tv)
		{
			if (in_array ($tv, $dbAllList['checkList']))
			{
				list ($targetTableName, $targetFieldName) = explode ('.', $tv);
				$dbAllList['relation'][$tableName][] = $targetTableName;
				changeorder ($targetTableName, $tableName);
				$gi = count ($dbAllList['handle'][$targetTableName]['get']);
				$dbAllList['handle'][$targetTableName]['get'][$gi]['field'] = $targetFieldName;
				$dbAllList['handle'][$targetTableName]['get'][$gi]['targetTable'] = $tableName;
				$dbAllList['handle'][$targetTableName]['get'][$gi]['targetField'] = $dbAllList['fieldList'][$tableName][$tk];
				++$gi;
				$pi = count ($dbAllList['handle'][$tableName]['put']);
				$dbAllList['handle'][$tableName]['put'][$pi]['field'] = $dbAllList['fieldList'][$tableName][$tk];
				$dbAllList['handle'][$tableName]['put'][$pi]['targetTable'] = $targetTableName;
				$dbAllList['handle'][$tableName]['put'][$pi]['targetField'] = $targetFieldName;
				++$pi;
				continue;
			}
		}
	}

	for ($i = 0; $i < count ($dbAllList['orderList']); ++$i)
	{
		$thisKey = $dbAllList['orderList'][$i];
		$nextKey = $i + 1;
		if (!empty ($dbAllList['relation'][$thisKey]))
		{
			if (in_array ($dbAllList['orderList'][$nextKey], $dbAllList['relation'][$thisKey]))
			{
				$temp = $dbAllList['orderList'][$i];
				$dbAllList['orderList'][$i] = $thisKey = $dbAllList['orderList'][$nextKey];
				$dbAllList['orderList'][$nextKey] = $temp;
				continue;
			}

			continue;
		}
	}

	$tag['title'] = '[标题]';
	$tag['body'] = '[内容]';
	$tag['date'] = '[采集时间]';
	$tag['url'] = '[网址]';
	$tag['author'] = '[作者]';
	$tag['from'] = '[来源]';
	$tag['intro'] = '[简介]';
	$tag['currentDate'] = '[时间]';
	$tag['articleID'] = '[文章编号]';
	$pregTagPattern['currentDateFormat'] = '/\\[时间格式:(.+?)\\]/ies';
	$pregTagPattern['dateFormat'] = '/\\[采集时间格式:(.+?)\\]/ies';
	$pregTagPattern['random'] = '/\\[随机:(.+?),(.+?)\\]/ies';
	if ($rs->get ('rules'))
	{
		$rulesArray = explode (',', $rs->get ('rules'));
		foreach ($rulesArray as $val)
		{
			$rulesSql .= $or . 'rules = ' . $val . ' ';
			$or = 'OR ';
		}

		$sqlRules = 'WHERE ' . $rulesSql;
	}

	if (!trim ($_GET['action']))
	{
		$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$sql = 'SELECT id, name ';
		$sql .= 'FROM ' . TB_RULES;
		$rs = $db->query ($sql);
		$i = 0;
		$rulesArray = explode (',', $ruleID);
		while ($rs->next_record ())
		{
			$list['option'][$i]['rulesID'] = $rs->get ('id');
			$list['option'][$i]['rulesName'] = $rs->get ('name');
			(in_array ($rs->get ('id'), $rulesArray) ? $list['option'][$i]['selected'] = ' selected' : $list['option'][$i]['selected'] = '');
			++$i;
		}

		$tp->set_templatefile ('templates/db_import_form.html');
		$tp->assign ('ID', $_GET['ID']);
		$tp->assign ($list);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '数据导入';
	}
	else
	{
		if ($_GET['action'] == 'saveRules')
		{
			if (!$_POST['rulesID'])
			{
				error ('你还没有选择要更新的采集器呢......');
			}

			$rules = implode (',', $_POST['rulesID']);
			$updateSql = 'UPDATE ' . TB_DB2DB . ' SET ';
			$updateSql .= ' rules = \'' . $rules . '\' WHERE id = ' . $_GET['ID'];
			$db->update ($updateSql);
			showloading ('index.php?module=importDB&ID=' . $_GET['ID'], '保存采集器成功...', '保存采集器列表成功，请进行导入设置.');
			$tpShowBody = false;
			exit ();
		}
		else
		{
			if ($_GET['action'] == 'getReady')
			{
				if (!$_POST['eachTimes'])
				{
					error ('请您输入每次采集的条数');
				}

				if (!is_numeric ($_POST['eachTimes']))
				{
					error ('每次采集条数只能是数字');
				}

				if ($_POST['eachTimes'] < 0)
				{
					error ('每次采集条数不能是负数');
				}

				$eachTimes = $_POST['eachTimes'];
				$filtrate = 0;
				if ($_POST['filtrate'])
				{
					$filtrate = 1;
				}

				switch ($_POST['convert'])
				{
					case 'u2g':
					{
						$isConvert = 'u2g';
						break;
					}

					case 'g2u':
					{
						$isConvert = 'g2u';
						break;
					}

					case 'none':
					{
						$isConvert = 'none';
						break;
					}

					default:
					{
						$isConvert = 'none';
						break;
					}
				}

				$sql = 'SELECT COUNT(id) AS total ';
				$sql .= 'FROM ' . TB_DATA . ' ';
				$sql .= $sqlRules;
				$rs = $db->query ($sql);
				$rs->next_record ();
				$total = $rs->get ('total');
				$db->disconnect ();
				if ($total < 1)
				{
					showloading ('?module=listDB', '取消任务...', '目前待导入的队列为空,返回导入配置列表.');
					$tpShowBody = false;
					exit ();
				}

				$url = '?module=importDB&action=processing&total=' . $total . '&ID=' . $_GET['ID'] . '&start=0&eachTimes=' . $eachTimes . '&filtrate=' . $filtrate . '&convert=' . $isConvert;
				showloading ($url, '数据导入开始', '现在开始按照您指定的导入配置执行导入任务,请耐心等待.');
				$tpShowBody = false;
			}
			else
			{
				if ($_GET['action'] == 'processing')
				{
					$total = intval ($_GET['total']);
					$eachTimes = intval ($_GET['eachTimes']);
					$start = intval ($_GET['start']);
					$insertTimes = intval ($_GET['insertTimes']);
					$existTimes = intval ($_GET['existTimes']);
					$filtrate = intval ($_GET['filtrate']);
					$isConvert = trim ($_GET['convert']);
					$sql = 'SELECT * ';
					$sql .= 'FROM ' . TB_DATA . ' ';
					$sql .= $sqlRules . ' ';
					$sql .= 'ORDER BY id DESC';
					$rs = $db->query ($sql, intval ($_GET['start']), $eachTimes);
					switch ($DB_TYPE)
					{
						case 'mysql':
						{
							$TDB = adonewconnection ('mysql');
							$TDB->Connect ($HOST, $USER, $PASS, $NAME);
							break;
						}

						case 'ado_access':
						{
							$myDSN = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;DATA SOURCE=' . realpath ($HOST) . ';' . 'USER ID=' . $USER . ';PASSWORD=' . $PASS . ';';
							$TDB = adonewconnection ('ado_access');
							$TDB->Connect ($myDSN, '', '', '');
							break;
						}

						case 'ado_mssql':
						{
							$myDSN = 'Driver={SQL Server};Server=' . $HOST . ';Database=' . $NAME . ';Uid=' . $USER . ';Pwd=' . $PASS . ';';
							$TDB = adonewconnection ('ado_mssql');
							$TDB->Connect ($myDSN, '', '', '');
							break;
						}

						default:
						{
							error ('不可识别或者不支持的数据库类型');
							break;
						}
					}

					$NBS = new NEATBulidSql ($dbAllList['orderList'][0]);
					while ($rs->next_record ())
					{
						switch ($DB_TYPE)
						{
							case 'mysql':
							{
								$contents['title'] = addslashes ($rs->get ('title'));
								$contents['body'] = addslashes ($rs->get ('body'));
								$contents['date'] = $rs->get ('date');
								$contents['url'] = addslashes ($rs->get ('url'));
								$contents['author'] = addslashes ($rs->get ('author'));
								$contents['from'] = addslashes ($rs->get ('data_from'));
								$contents['intro'] = addslashes ($rs->get ('intro'));
								$contents['currentDate'] = time ();
								$contents['articleID'] = $rs->get ('id');
//								var_dump($contents);die();
								$pregTagReplace['currentDateFormat'] = '@date(\'\\1\')';
								$pregTagReplace['dateFormat'] = '@date(\'\\1\', ' . $rs->get ('date') . ')';
								$pregTagReplace['random'] = '@getRandom(\'\\1\',\'\\2\')';
								break;
							}

							case 'ado_access':
							{
								$contents['title'] = str_replace ('\'', '\'\'', $rs->get ('title'));
								$contents['body'] = str_replace ('\'', '\'\'', $rs->get ('body'));
								$contents['date'] = $rs->get ('date');
								$contents['url'] = str_replace ('\'', '\'\'', $rs->get ('url'));
								$contents['author'] = str_replace ('\'', '\'\'', $rs->get ('author'));
								$contents['from'] = str_replace ('\'', '\'\'', $rs->get ('data_from'));
								$contents['intro'] = str_replace ('\'', '\'\'', $rs->get ('intro'));
								$contents['currentDate'] = time ();
								$contents['articleID'] = $rs->get ('id');
								$pregTagReplace['currentDateFormat'] = '@date(\'\\1\')';
								$pregTagReplace['dateFormat'] = '@date(\'\\1\', ' . $rs->get ('date') . ')';
								$pregTagReplace['random'] = '@getRandom(\'\\1\',\'\\2\')';
								break;
							}

							case 'ado_mssql':
							{
								$contents['title'] = str_replace ('\'', '\'\'', $rs->get ('title'));
								$contents['body'] = str_replace ('\'', '\'\'', $rs->get ('body'));
								$contents['date'] = $rs->get ('date');
								$contents['url'] = str_replace ('\'', '\'\'', $rs->get ('url'));
								$contents['author'] = str_replace ('\'', '\'\'', $rs->get ('author'));
								$contents['from'] = str_replace ('\'', '\'\'', $rs->get ('data_from'));
								$contents['intro'] = str_replace ('\'', '\'\'', $rs->get ('intro'));
								$contents['currentDate'] = time ();
								$contents['articleID'] = $rs->get ('id');
								$pregTagReplace['currentDateFormat'] = '@date(\'\\1\')';
								$pregTagReplace['dateFormat'] = '@date(\'\\1\', ' . $rs->get ('date') . ')';
								$pregTagReplace['random'] = '@getRandom(\'\\1\',\'\\2\')';
								break;
							}

							default:
							{
								error ('不可识别或者不支持的数据库类型');
								break;
							}
						}

						$rsNum = 0;
						foreach ($dbAllList['orderList'] as $tableKey => $tableName)
						{
							$checkData = array ();
							$importData = array ();
							foreach ($dbAllList['fieldList'][$tableName] as $k => $v)
							{
								$inTag = false;
								$data = preg_replace ($pregTagPattern, $pregTagReplace, $dbAllList['valueList'][$tableName][$k]);
								$data = str_replace ($tag, $contents, $data);
								switch ($isConvert)
								{
									case 'g2u':
									{
										$data = iconv ('GBK', 'UTF-8', $data);
										break;
									}

									case 'u2g':
									{
										$data = iconv ('UTF-8', 'GBK', $data);
										break;
									}

									case 'none':
									{
										break;
									}

									default:
									{
										break;
									}
								}

								if (strlen ($data))
								{
									$importData[$v] = $data;
								}

								foreach ($tag as $value)
								{
									if (strpos ($dbAllList['valueList'][$tableName][$k], $value) !== false)
									{
										$inTag = true;
										break;
									}
								}

								if ($inTag)
								{
									if ((strpos ($dbAllList['valueList'][$tableName][$k], '[时间]') === false AND strpos ($dbAllList['valueList'][$tableName][$k], '[采集时间]') === false))
									{
										$checkData[$v] = $data;
										continue;
									}

									continue;
								}
							}

							if ($dbAllList['handle'][$tableName]['put'])
							{
								foreach ($dbAllList['handle'][$tableName]['put'] as $putValue)
								{
									$importData[$putValue['field']] = $putArray[$tableName][$putValue['field']];
								}
							}

							$checkSql = 'SELECT * ';
							$checkSql .= 'FROM ' . $tableName . ' ';
							$num = count ($checkData);
							$i = 1;
							$checkSqlCondition = '';
							foreach ($checkData as $k => $v)
							{
								if ($DB_TYPE == 'ado_mssql')
								{
									$checkSqlCondition .= $k . ' like \'' . $v . '\'';
								}
								else
								{
									$checkSqlCondition .= $k . ' = \'' . $v . '\'';
								}

								if ($i < $num)
								{
									$checkSqlCondition .= ' AND ';
								}

								++$i;
							}

							if ($checkSqlCondition)
							{
								$checkSqlCondition = 'WHERE ' . $checkSqlCondition;
							}

							$checkSql .= $checkSqlCondition;
							if (($tableKey == 0 AND $filtrate))
							{
								$rs_check = $TDB->Execute ($checkSql);
								if ($rs_check)
								{
									$rsNum = $rs_check->RecordCount ();
									$rs_check->Close ();
								}
							}

							if ($rsNum == 0)
							{
								$NBS->setTable ($tableName);
								$sql = $NBS->add ($importData);
								$TDB->Execute ($sql);
								if ($dbAllList['handle'][$tableName]['get'])
								{
									$rsNewRow = array ();
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$rsNew = $TDB->Execute ($checkSql);
									if ($rsNew)
									{
										$rsNewRow = $rsNew->fields;
										$rsNew->Close ();
									}

									foreach ($dbAllList['handle'][$tableName]['get'] as $getValue)
									{
										$putArray[$getValue['targetTable']][$getValue['targetField']] = $rsNewRow[$getValue['field']];
									}

									continue;
								}

								continue;
							}
						}

						if ($rsNum == 0)
						{
							++$insertTimes;
							continue;
						}
						else
						{
							++$existTimes;
							continue;
						}
					}

					if (($recount_fieldsArray[0] AND 0 < $insertTimes))
					{
						foreach ($recount_fieldsArray as $k => $v)
						{
							$sql = 'UPDATE ' . $v . ' ';
							$sql .= 'SET ' . $recount_fieldsValueArray[$k] . ' = ' . $recount_fieldsValueArray[$k] . '+' . $insertTimes . ' ';
							$sql .= 'WHERE ' . $recount_rulesArray[$k] . ' = ' . $recount_rulesValueArray[$k];
							$TDB->Execute ($sql);
						}
					}

					$nextStart = $start + $eachTimes;
					$imported = $nextStart;
					$leaveTotal = $total - $imported;
					if ($total < $nextStart)
					{
						$url = '?module=listDB';
						showloading ('?module=listDB', '导入成功...', '成功导入 ' . $insertTimes . ' 条数据,过滤了 ' . $existTimes . ' 条数据');
						$tpShowBody = false;
					}
					else
					{
						$url = '?module=importDB&action=processing&total=' . $total . '&ID=' . $_GET['ID'] . '&start=' . $nextStart . '&insertTimes=' . $insertTimes . '&existTimes=' . $existTimes . '&eachTimes=' . $eachTimes . '&filtrate=' . $filtrate . '&convert=' . $isConvert;
						$message = '当前已经处理 : ' . $imported . ' 条,还有 ' . $leaveTotal . ' 条在任务队列中. (一共 ' . $total . ' 条)';
						showloading ($url, '数据入库任务进行中...', $message, 1);
						$tpShowBody = false;
					}

					$db->disconnect ();
					$TDB->Close ();
				}
			}
		}
	}

?>
