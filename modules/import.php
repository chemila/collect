<?
$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE, 1);
if (!trim ($_GET['action']))
{
	$sql = 'SELECT id, name ';
	$sql .= 'FROM ' . TB_RULES . ' ';
	$sql .= 'ORDER BY id DESC';
	$rs = $db->query ($sql);
	$i = 0;
	while ($rs->next_record ())
	{
		$list['option'][$i]['rulesID'] = $rs->get ('id');
		$list['option'][$i]['rulesName'] = $rs->get ('name');
		($rs->get ('id') == $_GET['rules'] ? $list['option'][$i]['rulesSelected'] = ' selected' : $list['option'][$i]['rulesSelected'] = '');
		++$i;
	}

	$tp->set_templatefile ('templates/import_form.html');
	$tp->assign ($list);
	$tp->assign ('rulesID', $_GET['rules']);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '数据入库';
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

		$rulesID = $_POST['rulesID'];
		$eachTimes = $_POST['eachTimes'];
		$totalGet = $_POST['total'];
		if ($rulesID)
		{
			$sqlRules = 'AND rules = ' . $rulesID . ' ';
		}

		$sql = 'SELECT COUNT(*) AS total ';
		$sql .= 'FROM ' . TB_LINKS . ' ';
		$sql .= 'WHERE adopt = 1 AND import = 0 ';
		$sql .= $sqlRules;
		$rs = $db->query ($sql);
		$rs->next_record ();
		$total = $rs->get ('total');
		if ($total < 1)
		{
			showloading ('?module=listRules', '取消任务...', '目前待采集的队列为空,返回采集器列表.');
			$tpShowBody = false;
			exit ();
		}

		if ($totalGet)
		{
			($total < $totalGet ? $total = $total : $total = $totalGet);
		}

		if ($total < $eachTimes)
		{
			$eachTimes = $total;
		}

		$url = '?module=import&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&start=1';
		showloading ($url, '数据入库开始', '现在开始按照您的设定执行入库任务,请耐心等待.');
		$tpShowBody = false;
	}
	else
	{
		if ($_GET['action'] == 'processing')
		{
			$total = intval ($_GET['total']);
			$eachTimes = intval ($_GET['eachTimes']);
			$start = intval ($_GET['start']);
			$rulesID = intval ($_GET['rulesID']);
			$badLinkID = array ();
			if ($rulesID)
			{
				$sqlRules = 'AND rules = ' . $rulesID . ' ';
			}

			$sql = 'SELECT id, title, url, rules ';
			$sql .= 'FROM ' . TB_LINKS . ' ';
			$sql .= 'WHERE adopt = 1 AND import = 0 ';
			$sql .= $sqlRules;
			$sql .= 'ORDER BY id ASC';
			$rs = $db->query ($sql, 0, $eachTimes);
			while ($rs->next_record ())
			{
				$thisRulesID = $rs->get ('rules');
				if (empty ($rules[$thisRulesID]))
				{
					$sql = 'SELECT * ';
					$sql .= 'FROM ' . TB_RULES . ' ';
					$sql .= 'WHERE id = ' . $thisRulesID;
					$rs_rules = $db->query ($sql);
					$rs_rules->next_record ();
					$filterSql = 'SELECT * ';
					$filterSql .= 'FROM ' . TB_FILTER . ' ';
					$filterSql .= 'WHERE rule_id = ' . $thisRulesID . ' ';
					$filterSql .= 'ORDER BY id ASC';
					$rsFilter = $db->query ($filterSql);
					$i = 0;
					while ($rsFilter->next_record ())
					{
						$filter[$i] = $rsFilter->get ('filter_rule');
						++$i;
					}

					$rules[$thisRulesID]['tag']['variable'] = '[变数]';
					$rules[$thisRulesID]['tag']['title'] = '[标题]';
					$rules[$thisRulesID]['tag']['body'] = '[内容]';
					$rules[$thisRulesID]['tag']['link'] = '[连接]';
					$rules[$thisRulesID]['tag']['body_page'] = '[分页区域]';
					$rules[$thisRulesID]['tag']['author'] = '[作者]';
					$rules[$thisRulesID]['tag']['from'] = '[来源]';
					$rules[$thisRulesID]['tag']['intro'] = '[简介]';
					$rules[$thisRulesID]['area']['title'] = $rs_rules->get ('area_title');
					$rules[$thisRulesID]['area']['body'] = $rs_rules->get ('area_body');
					$rules[$thisRulesID]['area']['body_page'] = $rs_rules->get ('area_body_page');
					$rules[$thisRulesID]['area']['body_page_link'] = $rs_rules->get ('area_body_page_link');
					$rules[$thisRulesID]['area']['author'] = $rs_rules->get ('area_author');
					$rules[$thisRulesID]['area']['from'] = $rs_rules->get ('area_from');
					$rules[$thisRulesID]['area']['intro'] = $rs_rules->get ('area_intro');
					$rules[$thisRulesID]['area']['filter'] = $filter;
					($rs_rules->get ('multi_title') == 1 ? $rules[$thisRulesID]['multi']['title'] = 1 : $rules[$thisRulesID]['multi']['title'] = 2);
					($rs_rules->get ('multi_body') == 1 ? $rules[$thisRulesID]['multi']['body'] = 1 : $rules[$thisRulesID]['multi']['body'] = 2);
					($rs_rules->get ('multi_body_page') == 1 ? $rules[$thisRulesID]['multi']['body_page'] = 1 : $rules[$thisRulesID]['multi']['body_page'] = 2);
					($rs_rules->get ('multi_body_page_link') == 1 ? $rules[$thisRulesID]['multi']['body_page_link'] = 1 : $rules[$thisRulesID]['multi']['body_page_link'] = 2);
					($rs_rules->get ('multi_author') == 1 ? $rules[$thisRulesID]['multi']['author'] = 1 : $rules[$thisRulesID]['multi']['author'] = 2);
					($rs_rules->get ('multi_from') == 1 ? $rules[$thisRulesID]['multi']['from'] = 1 : $rules[$thisRulesID]['multi']['from'] = 2);
					($rs_rules->get ('multi_intro') == 2 ? $rules[$thisRulesID]['multi']['intro'] = 1 : $rules[$thisRulesID]['multi']['intro'] = 2);
					($rs_rules->get ('enter_title') == 0 ? $rules[$thisRulesID]['format']['title'] = 1 : $rules[$thisRulesID]['format']['title'] = 2);
					($rs_rules->get ('enter_body') == 0 ? $rules[$thisRulesID]['format']['body'] = 1 : $rules[$thisRulesID]['format']['body'] = 2);
					($rs_rules->get ('enter_body_page') == 0 ? $rules[$thisRulesID]['format']['body_page'] = 1 : $rules[$thisRulesID]['format']['body_page'] = 2);
					($rs_rules->get ('enter_body_page_link') == 0 ? $rules[$thisRulesID]['format']['body_page_link'] = 1 : $rules[$thisRulesID]['format']['body_page_link'] = 2);
					($rs_rules->get ('enter_author') == 0 ? $rules[$thisRulesID]['format']['author'] = 1 : $rules[$thisRulesID]['format']['author'] = 2);
					($rs_rules->get ('enter_from') == 0 ? $rules[$thisRulesID]['format']['from'] = 1 : $rules[$thisRulesID]['format']['from'] = 2);
					($rs_rules->get ('enter_intro') == 0 ? $rules[$thisRulesID]['format']['intro'] = 1 : $rules[$thisRulesID]['format']['intro'] = 2);
					$rules[$thisRulesID]['bodyPageType'] = $rs_rules->get ('body_page_type');
					$rules[$thisRulesID]['method'] = 'GET';
					$rules[$thisRulesID]['param']['cookie'] = $rs_rules->get ('cookies');
				}

				$NC = new NEAT_COLLECTOR ();
				$NIA = new NEAT_IMPORT_ARTICLE ($NC);
				$articleData = $NIA->getArticle ($rs->get ('url'), $rules[$thisRulesID]['tag'], $rules[$thisRulesID]['area'], $rules[$thisRulesID]['multi'], $rules[$thisRulesID]['format'], $rules[$thisRulesID]['method'], $rules[$thisRulesID]['param'], $rules[$thisRulesID]['bodyPageType'], $rs_rules->get ('referer'), $rs_rules->get ('useragent'), $rs_rules->get ('replaceRNT'));
				if (!$articleData)
				{
					$badLinkID[] = $rs->get ('id');
					continue;
				}
				else
				{
					$importTitle = &$articleData['title'];
					$importBody = &$articleData['body'];
					$importAuthor = &$articleData['author'];
					$importFrom = &$articleData['from'];
					$importIntro = &$articleData['intro'];
					$importDate = strtotime (date ('Y-m-d H:i:s'));
//					if ((trim ($importTitle) AND trim ($importBody)))
//@todo
					if ((trim ($importTitle)))
					{
						$NBS = new NEATBulidSql (TB_DATA);
						$dataFids['id'] = '';
						$dataFids['link_id'] = $rs->get ('id');
						$dataFids['rules'] = $thisRulesID;
						$dataFids['title'] = addslashes ($importTitle);
						$dataFids['body'] = addslashes ($importBody);
						$dataFids['author'] = addslashes ($importAuthor);
						$dataFids['data_from'] = addslashes ($importFrom);
						$dataFids['intro'] = addslashes ($importIntro);
						$dataFids['url'] = addslashes ($rs->get ('url'));
						$dataFids['date'] = $importDate;
						$sql = $NBS->add ($dataFids);
						$db->update ($sql);
						$importID[] = $rs->get ('id');
						continue;
					}
					else
					{
						$badLinkID[] = $rs->get ('id');
						continue;
					}

					continue;
				}
			}

			if ($badLinkID)
			{
				$badLinkIdNum = count ($badLinkID);
				$ii = 1;
				foreach ($badLinkID as $key => $val)
				{
					++$ii;
					$sqlAdopt .= $val;
					if ($ii <= $badLinkIdNum)
					{
						$sqlAdopt .= ', ';
						continue;
					}
				}

				$sql = 'UPDATE ' . TB_LINKS . ' ';
				$sql .= 'SET adopt = 0 ';
				$sql .= 'WHERE id ';
				$sql .= 'IN (' . $sqlAdopt . ')';
				$db->update ($sql);
			}

			$idNum = count ($importID);
			if (1 <= $idNum)
			{
				$i = 1;
				foreach ($importID as $k => $v)
				{
					++$i;
					$sqlImport .= $v;
					if ($i <= $idNum)
					{
						$sqlImport .= ', ';
						continue;
					}
				}

				$sql = 'UPDATE ' . TB_LINKS . ' ';
				$sql .= 'SET import = 1 ';
				$sql .= 'WHERE id ';
				$sql .= 'IN (' . $sqlImport . ')';
				$db->update ($sql);
			}

			$nextStart = $start + $eachTimes;
			$geted = $nextStart - 1;
			$leaveTotal = $total - $geted;
			if ($leaveTotal < $eachTimes)
			{
				$eachTimes = $leaveTotal;
			}

			if ($total < $nextStart)
			{
				$countSql = 'SELECT COUNT(*) AS total ';
				$countSql .= 'FROM ' . TB_DATA . ' ';
				$countSql .= 'WHERE rules = ' . $_GET['rulesID'];
				$rs = $db->query ($countSql);
				$rs->next_record ();
				$importTotal = $rs->get ('total');
				$NBS = new NEATBulidSql (TB_RULES);
				$conditionFids['id'] = $_GET['rulesID'];
				$rulesFids['import_num'] = $importTotal;
				$sql = $NBS->update ($rulesFids, $conditionFids);
				$db->update ($sql);
				$url = ($_GET['rulesID'] ? '?module=updateRulesCount&ID=' . $_GET['rulesID'] : 'index.php');
				showloading ($url, '数据入库任务完成...', '文章已经全部采集到本地数据库');
				$tpShowBody = false;
			}
			else
			{
				$url = '?module=import&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&start=' . $nextStart;
				$message = '当前已经入库 : ' . $geted . ' 条,还有 ' . $leaveTotal . ' 条在任务队列中. (一共 ' . $total . ' 条)';
				showloading ($url, '数据入库任务进行中...', $message, 1);
				$tpShowBody = false;
			}
		}
	}
}

$db->disconnect ();
?>
