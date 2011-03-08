<?

	if (!$_GET['ID'])
	{
		error ('采集器编号不能为空!');
	}

	if (!is_numeric ($_GET['ID']))
	{
		error ('采集器编号只能是数字!');
	}

	if (!$_GET['action'])
	{
		showloading ('?module=testLink&action=get&ID=' . $_GET['ID'], '测试采集连接中...', '这会根据您的网络速度以及目标站的网络速度来决定消耗时间.请耐心等待! ', 1);
		$tpShowBody = false;
	}
	else
	{
		if ($_GET['action'] == 'get')
		{
			$sql = 'SELECT * ';
			$sql .= 'FROM ' . TB_RULES . ' ';
			$sql .= 'WHERE id = ' . $_GET['ID'];
			$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
			$rs = $db->query ($sql);
			$db->disconnect ();
			if (!$rs->next_record ())
			{
				error ('找不到编号为' . $_GET['ID'] . '的采集器规则!');
			}

			$NC = new NEAT_COLLECTOR ();
			$NIA = new NEAT_IMPORT_ARTICLE ($NC);
			switch ($rs->get ('index_type'))
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
					$temp = explode ("\r\n", $rs->get ('url'));
					$url = $temp[0];
					break;
				}

				case 3:
				{
					if ($rs->get ('method') == 1)
					{
						$method = 'GET';
						$param['cookie'] = $rs->get ('cookies');
						$referer = $rs->get ('referer');
						$useragent = $rs->get ('useragent');
			$replaceRNT = $rs->get ('replaceRNT');
						$urlList = $NIA->MultiLinksByGET ($rs->get ('url'), $rs->get ('page_start'), $rs->get ('page_end'), $rs->get ('page_rules'));
						$url = $urlList[0];
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
						$param['post'] = $postList[0];
						$url = $rs->get ('url');
					}
				}
			}

			$fullURL = $url;
			if (75 < strlen ($url))
			{
				$url = c_substr ($url, 0, 75) . '...';
			}

			$tag['variable'] = '[变数]';
			$tag['link'] = '[连接]';
			$tag['title'] = '[标题]';
			$area['links'] = $rs->get ('area_link');
			($rs->get ('multi_link') == 1 ? $areaMulti = 1 : $areaMulti = 2);
			($rs->get ('enter_link') == 0 ? $areaFormat = 1 : $areaFormat = 2);
		
			$linksList = $NIA->getLinks ($fullURL, $tag, $area, $areaMulti, $areaFormat, $method, $param, $rs->get ('link_replace'), $referer, $useragent, $replaceRNT);
			$i = 0;
			$report['none']['title'] = 0;
			$report['have']['title'] = 0;
			$report['none']['link'] = 0;
			$report['have']['link'] = 0;
			if (!empty ($linksList['link']))
			{
				foreach ($linksList['link'] as $k => $v)
				{
					if (in_array (getextension ($v), $configIgnoreExt))
					{
						continue;
					}
					else
					{
						$title = deletehtml ($linksList['title'][$k]);
						if (60 < strlen ($title))
						{
							$title = m_substr ($title, 0, 60) . chr (0) . '...';
						}

						$list['list'][$i]['title'] = $title;
						if (60 < strlen ($v))
						{
							$list['list'][$i]['link'] = c_substr ($v, 0, 60) . '...';
						}
						else
						{
							$list['list'][$i]['link'] = str_replace ('&amp;', '&', $v);
						}

						$list['list'][$i]['fulllink'] = $v;
						$list['list'][$i]['getLink'] = rawurlencode (str_replace ('&amp;', '&', $v));
						$list['list'][$i]['rules'] = $_GET['ID'];
						++$i;
						if ($linksList['title'][$k] == '')
						{
							++$report['none']['title'];
						}
						else
						{
							++$report['have']['title'];
						}

						if ($v == '')
						{
							++$report['none']['link'];
							continue;
						}
						else
						{
							++$report['have']['link'];
							continue;
						}

						continue;
					}
				}
			}

			if (($report['none']['title'] == 0 AND $report['none']['link'] == 0))
			{
				$analyse = '标题和连接都能全部采集到内容，工作正常。';
			}

			if (($report['none']['title'] == count ($linksList['link']) AND $report['none']['link'] == count ($linksList['link'])))
			{
				$analyse = '标题和连接不能正确采集，请重新编写规则。';
			}

			$tp->set_templatefile ('templates/list_links.html');
			$tp->assign ($list);
			$tp->assign ('id', $_GET['ID']);
			$tp->assign ('rulesName', $rs->get ('name'));
			$tp->assign ('indexLink', $url);
			$tp->assign ('fullIndexLink', $fullURL);
			$tp->assign ('totalLinksCount', count ($linksList['link']));
			$tp->assign ('filtrateLinksCount', 0);
			$tp->assign ('tautologyLinksCount', $NIA->getTautologyLinksCount ());
			$tp->assign ('noneTitle', $report['none']['title']);
			$tp->assign ('haveTitle', $report['have']['title']);
			$tp->assign ('noneLink', $report['none']['link']);
			$tp->assign ('haveLink', $report['have']['link']);
			$tp->assign ('analyse', $analyse);
			$moduleTemplate = $tp->result ();
			$moduleTitle = '连接采集测试';
		}
	}

?>
