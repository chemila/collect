<?

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

	$baseURL = '?module=collectionLink&action=get&ID=' . $_GET['ID'] . '&type=';
	if (!$_GET['action'])
	{
		switch ($rs->get ('index_type'))
		{
			case 1:
			{
				$url = 1;
				break;
			}

			case 2:
			{
				$url = '2&start=1';
				break;
			}

			case 3:
			{
				$url = '3&start=1';
			}
		}

		$url = $baseURL . $url . '&dataCount=0&existsCount=0';
		showloading ($url);
		$tpShowBody = false;
	}
	else
	{
		if ($_GET['action'] == 'get')
		{
			$NC = new NEAT_COLLECTOR ();
			$NIA = new NEAT_IMPORT_ARTICLE ($NC);
			switch ($_GET['type'])
			{
				case 1:
				{
					$method = 'GET';
					$param['cookie'] = $rs->get ('cookies');
			$referer = $rs->get ('referer');
					$useragent = $rs->get ('useragent');
			$replaceRNT = $rs->get ('replaceRNT');
					$url = $rs->get ('url');
					break;
				}

				case 2:
				{
					$method = 'GET';
					$param['cookie'] = $rs->get ('cookies');
			$referer = $rs->get ('referer');
					$useragent = $rs->get ('useragent');
			$replaceRNT = $rs->get ('replaceRNT');
					$key = $_GET['start'] - 1;
					$urlList = explode ("\r\n", $rs->get ('url'));
					$pageTotal = count ($urlList);
					$url = $urlList[$key];
					break;
				}

				case 3:
				{
					$key = $_GET['start'] - 1;
					if ($rs->get ('method') == 1)
					{
						$method = 'GET';
						$param['cookie'] = $rs->get ('cookies');
			$referer = $rs->get ('referer');
						$useragent = $rs->get ('useragent');
			$replaceRNT = $rs->get ('replaceRNT');
						$urlList = $NIA->MultiLinksByGET ($rs->get ('url'), $rs->get ('page_start'), $rs->get ('page_end'), $rs->get ('page_rules'));
						$pageTotal = count ($urlList);
						$url = $urlList[$key];
						break;
					}
					else
					{
						$method = 'POST';
						$param['cookie'] = $rs->get ('cookies');
			$referer = $rs->get ('referer');
						$useragent = $rs->get ('useragent');
			$replaceRNT = $rs->get ('replaceRNT');
						$postList = $NIA->MultiLinksByPOST ($rs->get ('posts'), $rs->get ('page_start'), $rs->get ('page_end'), $rs->get ('page_rules'));
						$param['post'] = $postList[$key];
						$pageTotal = count ($postList);
						$url = $rs->get ('url');
					}
				}
			}

			$tag['variable'] = '[变数]';
			$tag['link'] = '[连接]';
			$tag['title'] = '[标题]';
			$area['links'] = $rs->get ('area_link');
			($rs->get ('multi_link') == 1 ? $areaMulti = 1 : $areaMulti = 2);
			($rs->get ('enter_link') == 0 ? $areaFormat = 1 : $areaFormat = 2);
			$linksList = $NIA->getLinks ($url, $tag, $area, $areaMulti, $areaFormat, $method, $param, $rs->get ('link_replace'), $referer, $useragent, $replaceRNT);
			$NBS = new NEATBulidSql (TB_LINKS);
			$existsCount = $_GET['existsCount'];
			$dataCount = $_GET['dataCount'];
			foreach ($linksList['link'] as $k => $v)
			{
				if (in_array (getextension ($v), $configIgnoreExt))
				{
					continue;
				}
				else
				{
					$link = &$v;
					$sqlSearch = 'SELECT id ';
					$sqlSearch .= 'FROM ' . TB_LINKS . ' ';
					$sqlSearch .= 'WHERE url = \'' . $link . '\' ';
					$sqlSearch .= 'Limit 1';
					$rs = $db->query ($sqlSearch);
					if (!$rs->next_record ())
					{
						++$dataCount;
						$dataFids['id'] = '';
						$dataFids['title'] = deletehtml (addslashes ($linksList['title'][$k]));
						$dataFids['url'] = addslashes (str_replace ('&amp;', '&', $link));
						$dataFids['rules'] = $_GET['ID'];
						$dataFids['date'] = strtotime (date ('Y-m-d H:i:s'));
						$sql = $NBS->add ($dataFids);
						$db->update ($sql);
						continue;
					}
					else
					{
						++$existsCount;
						continue;
					}

					continue;
				}
			}

			$finishBaseURL = '?module=collectionLink&action=finish&ID=' . $_GET['ID'];
			$nextAlertTitle = '继续采集...';
			$nextAlertMessage = '继续采集下一页索引的连接.请不要关闭本页.';
			$finishAlertTitle = '采集完成...';
			$finishAlertMessage = '连接采集完成,现在开始统计结果.';
			if ($_GET['type'] == 1)
			{
				$gotoURL = $finishBaseURL . '&dataCount=' . $dataCount . '&existsCount=' . $existsCount;
				$alertTitle = $finishAlertTitle;
				$alertMessage = $finishAlertMessage;
			}
			else
			{
				if ($_GET['type'] == 2)
				{
					if ($_GET['start'] < $pageTotal)
					{
						$alertTitle = $nextAlertTitle;
						$alertMessage = '当前任务:第' . $_GET['start'] . '页,一共' . $pageTotal . '页在任务队列中.' . $nextAlertMessage;
						$gotoURL = $baseURL . '2&start=' . ++$_GET['start'] . '&dataCount=' . $dataCount . '&existsCount=' . $existsCount;
					}
					else
					{
						$gotoURL = $finishBaseURL . '&dataCount=' . $dataCount . '&existsCount=' . $existsCount;
						$alertTitle = $finishAlertTitle;
						$alertMessage = $finishAlertMessage;
					}
				}
				else
				{
					if ($_GET['start'] < $pageTotal)
					{
						$alertTitle = $nextAlertTitle;
						$alertMessage = '当前任务:第' . $_GET['start'] . '页,一共' . $pageTotal . '页在任务队列中.' . $nextAlertMessage;
						$gotoURL = $baseURL . '3&start=' . ++$_GET['start'] . '&dataCount=' . $dataCount . '&existsCount=' . $existsCount;
					}
					else
					{
						$gotoURL = $finishBaseURL . '&dataCount=' . $dataCount . '&existsCount=' . $existsCount;
						$alertTitle = $finishAlertTitle;
						$alertMessage = $finishAlertMessage;
					}
				}
			}

			showloading ($gotoURL, $alertTitle, $alertMessage, 1);
			$tpShowBody = false;
		}
		else
		{
			if ($_GET['action'] == 'finish')
			{
				if (0 < $_GET['dataCount'])
				{
					$NBS = new NEATBulidSql (TB_RULES);
					$updateFids['link_num'] = 'link_num';
					$conditionFids['id'] = $_GET['ID'];
					$config['link_num']['method'] = '+';
					$config['link_num']['num'] = $_GET['dataCount'];
					$sql = $NBS->update ($updateFids, $conditionFids, $config);
					$db->update ($sql);
				}

				$tp->set_templatefile ('templates/link_collection_result.html');
				$tp->assign ('existsCount', $_GET['existsCount']);
				$tp->assign ('dataCount', $_GET['dataCount']);
				$moduleTemplate = $tp->result ();
				$moduleTitle = '采集统计';
			}
		}
	}

	$db->disconnect ();
?>
