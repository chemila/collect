<?
class neat_import_article
{
	public $NC = null;
	public $linkTagName = 'link';
	public $titleTagName = 'title';
	public $bodyTagName = 'body';
	public $bodyPageTagName = 'body_page';
	public $authorTagName = 'author';
	public $fromTagName = 'from';
	public $introTagName = 'intro';
	public $linksAreaName = 'links';
	public $titleAreaName = 'title';
	public $bodyAreaName = 'body';
	public $bodyPageAreaName = 'body_page';
	public $bodyPageLinkAreaName = 'body_page_link';
	public $authorAreaName = 'author';
	public $fromAreaName = 'from';
	public $introAreaName = 'intro';
	public $filterAreaName = 'filter';
	public $tautologyLinksCount = 0;
	public $tagPattern = array ('variable' => '(.*?)', 'link' => '([^"\'<> ]+)', 'title' => '(.*?)', 'body' => '(.*?)', 'body_page' => '(.*?)', 'author' => '(.*?)', 'from' => '(.*?)', 'intro' => '(.*?)');
	function neat_import_article ($NCObject = '')
	{
		if ($NCObject)
		{
			$this->setNCObject ($NCObject);
		}

	}

	function setncobject (&$obj)
	{
		$this->NC = &$obj;
	}

	function getlinks ($url, $tag, $area, $areaMulti, $areaFormat, $method, $param, $replace = '', $referer, $useragent, $replaceRNT)
	{

		$this->NC->setPageURL ($url);
		$this->NC->setTag ($tag);
		$this->NC->setTagPattern ($this->tagPattern);
		$this->NC->setArea ($area);
		$this->NC->GetContentsByFsockopen ($url, $method, $param, $referer, $useragent, $replaceRNT);
		$result = $this->NC->pickup ($this->linksAreaName, $areaMulti, $areaFormat);
		$baseURL = $this->NC->getBaseURL ($this->NC->contents);
		if ($baseURL)
		{
			$this->NC->setPageURL ($baseURL);
		}

		if (!empty ($result[$this->linkTagName][0]))
		{
			$data[$this->linkTagName] = array ();
			$data[$this->titleTagName] = array ();
			foreach ($result[$this->linkTagName][0] as $k => $v)
			{
				if (!in_array ($v, $data[$this->linkTagName]))
				{
					if ($replace)
					{
						$data[$this->linkTagName][] = str_replace ('[文章编号]', $v, $replace);
					}
					else
					{
						$data[$this->linkTagName][] = $this->NC->fixPath ($v);
					}

					$data[$this->titleTagName][] = $result[$this->titleTagName][0][$k];
					continue;
				}
				else
				{
					++$this->tautologyLinksCount;
					continue;
				}
			}
		}

		return $data;
	}

	function getarticle ($url, $tag, $area, $areaMulti, $areaFormat, $method, $param, $bodyPageType = '0', $referer, $useragent, $replaceRNT)
	{
		global $NeatReged;				
		//var_dump($area);
		$this->NC->setTag ($tag);
		$this->NC->setTagPattern ($this->tagPattern);
		$this->NC->setArea ($area);
		$this->NC->setPageURL ($url);
		$this->NC->GetContentsByFsockopen ($url, $method, $param, $referer, $useragent, $replaceRNT);
		if (!$this->NC->contents)
		{
			return false;
		}

		$baseURL = $this->NC->getBaseURL ($this->NC->contents);
		if ($baseURL)
		{
			$this->NC->setPageURL ($baseURL);
		}

		$titleResult = $this->NC->pickup ($this->titleAreaName, $areaMulti[$this->titleAreaName], $areaFormat[$this->titleAreaName]);
		$data['title'] = $titleResult[$this->titleTagName][0][0];
		if ($area[$this->authorAreaName])
		{
			$authorResult = $this->NC->pickup ($this->authorAreaName, $areaMulti[$this->authorAreaName], $areaFormat[$this->authorAreaName]);
			$data['author'] = $authorResult[$this->authorTagName][0][0];
		}

		if ($area[$this->fromAreaName])
		{
			$fromResult = $this->NC->pickup ($this->fromAreaName, $areaMulti[$this->fromAreaName], $areaFormat[$this->fromAreaName]);
			$data['from'] = $fromResult[$this->fromTagName][0][0];
		}

		if ($area[$this->introAreaName])
		{
			$introResult = $this->NC->pickup ($this->introAreaName, $areaMulti[$this->introAreaName], $areaFormat[$this->introAreaName]);
			$data['intro'] = $introResult[$this->introTagName][0][0];
		}

		$bodyResult = $this->NC->pickup ($this->bodyAreaName, $areaMulti[$this->bodyAreaName], $areaFormat[$this->bodyAreaName]);
		$data['body'] = $bodyResult[$this->bodyTagName][0][0];
		if ($area[$this->bodyPageAreaName])
		{
			if ($bodyPageType == '0')
			{
				$this->NC->pickup ($this->bodyPageAreaName, $areaMulti[$this->bodyPageAreaName], $areaFormat[$this->bodyPageAreaName]);
				preg_match_all ('/(href=("|\\\'|))([^"\'<> ]+)("|\\\'|)/is', $this->NC->result[$this->bodyPageAreaName][$this->bodyPageAreaName][0][0], $hrefMatch);
				$pageArray = $hrefMatch[3];
				$getedList = array ($url);
				$getedPages = 1;
				foreach ($pageArray as $k => $v)
				{
					unset ($this->NC[result]);
					$page_url = $this->NC->fixPath ($v);
					if (!in_array ($page_url, $getedList))
					{
						$this->NC->getContents ($page_url);
						$tmpBodyResult = $this->NC->pickup ($this->bodyTagName, $areaMulti[$this->bodyTagName], $areaFormat[$this->bodyTagName]);
						$data['body'] .= $tmpBodyResult[$this->bodyTagName][0][0];
						$getedList[] = $page_url;
						++$getedPages;
						continue;
					}
				}

				$data['pages'] = $getedPages;
			}
			else
			{
				if ($bodyPageType == '1')
				{
					$tmpBodyPageLinkResult = $this->NC->pickup ($this->bodyPageLinkAreaName, $areaMulti[$this->bodyPageLinkAreaName], $areaFormat[$this->bodyPageLinkAreaName]);
					if ($this->NC->result['body_page_link']['link'][0][0])
					{
						$url = $this->NC->fixPath ($tmpBodyPageLinkResult[$this->linkTagName][0][0]);
					}
					else
					{
						$url = '';
					}

					$getedPages = 1;
					while ($url)
					{
						unset ($this->NC[result]);
						$this->NC->setPageURL ($url);
						$this->NC->getContents ($url);
						$baseURL = $this->NC->getBaseURL ($this->NC->contents);
						if ($baseURL)
						{
							$this->NC->setPageURL ($baseURL);
						}

						$tmpBodyPageResult = $this->NC->pickup ($this->bodyPageAreaName, $areaMulti[$this->bodyPageAreaName], $areaFormat[$this->bodyPageAreaName]);
						$tmpBodyResult = $this->NC->pickup ($this->bodyAreaName, $areaMulti[$this->bodyAreaName], $areaFormat[$this->bodyAreaName]);
						$data['body'] .= $tmpBodyResult[$this->bodyTagName][0][0];
						$this->NC->setContents ($tmpBodyPageResult[$this->bodyPageTagName][0][0]);
						$tmpBodyPageLinkResult = $this->NC->pickup ($this->bodyPageLinkAreaName, $areaMulti[$this->bodyPageLinkAreaName], $areaFormat[$this->bodyPageLinkAreaName]);
						if ($this->NC->result['body_page_link']['link'][0][0])
						{
							$url = $this->NC->fixPath ($tmpBodyPageLinkResult[$this->linkTagName][0][0]);
						}
						else
						{
							$url = '';
						}

						++$getedPages;
					}

					$data['pages'] = $getedPages;
				}
			}
		}

		$filter = $area['filter'];
		if ($filter)
		{
			foreach ($filter as $val)
			{
				$this->NC->area[$this->filterAreaName] = $val;
				$filterArea = $this->NC->getReplacedArea ($this->filterAreaName);
				$data['body'] = preg_replace ('/' . $filterArea . '/is', '', $data['body']);
			}
		}

		$data['body'] = $this->fixImageLink ($data['body']);
		return $data;
	}

	function fiximagelink ($contents)
	{
		$this->imageLinks = array ();
		preg_match_all ('/(<img(.+?)src=("|\\\'|))([^"\'<> ]+)("|\\\'|)/is', $contents, $imgMatch);
		$this->imageLinks = $imgMatch[4];
		foreach ($this->imageLinks as $k => $v)
		{
			$contents = str_replace ($v, $this->NC->fixPath ($v), $contents);
		}

		return $contents;
	}

	function gettautologylinkscount ()
	{
		return $this->tautologyLinksCount;
	}

	function multilinksbyget ($url, $start, $end, $page_rules, $format = 1)
	{
		//获取page_rules [相乘][增加][补位] 
		$page_rules = @explode(',', $page_rules );
		$page_rules_mula =$page_rules[0]; //乘法
		$page_rules_add = $page_rules[1]; //加法
		$page_rules_fill =$page_rules[2]; //补位

		if ($end < $start)
		{
			return false;
		}
		for ($i = $start; $i <= $end; ++$i)
		{
			//判断乘法是否为空,如果为空,就不相乘,直接相加.
			if ($page_rules_mula == '')
			{
				$end_calculate = $i + $page_rules_add; //开始先相加计算;
			}else
			{
				$end_calculate = $i * $page_rules_mula + $page_rules_add; //开始先乘后加 计算;
			}
			 
			$end_calculate = $end_calculate.$page_rules_fill; //补位

			$formatedNumeric = sprintf ('%0' . $format . '.0f', $end_calculate);
			$linksList[] = str_replace ('[分页]', $formatedNumeric, $url);
		}

		return $linksList;
	}

	function multilinksbypost ($post, $start, $end, $page_rules, $format = 1)
	{
		//获取page_rules [相乘][增加][补位] 
		$page_rules = @explode(',', $page_rules );
		$page_rules_mula =$page_rules[0]; //乘法
		$page_rules_add = $page_rules[1]; //加法
		$page_rules_fill =$page_rules[2]; //补位
		if ($end < $start)
		{
			return false;
		}

		for ($i = $start; $i <= $end; ++$i)
		{
			//判断乘法是否为空,如果为空,就不相乘,直接相加.
			if ($page_rules_mula == '')
			{
				$end_calculate = $i + $page_rules_add; //开始先相加计算;
			}else
			{
				$end_calculate = $i * $page_rules_mula + $page_rules_add; //开始先乘后加 计算;
			}
	
			$end_calculate = $end_calculate.$page_rules_fill; //补位

			$formatedNumeric = sprintf ('%0' . $format . '.0f', $end_calculate);
			$linksList[] = str_replace ('[分页]', $formatedNumeric, $post);
		}

		return $linksList;
	}
}


?>