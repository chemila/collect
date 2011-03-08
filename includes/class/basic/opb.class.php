<?
	class opb
	{
		var $total = null;
		var $onepage = null;
		var $num = null;
		var $page = null;
		var $total_page = null;
		var $offset = null;
		var $linkhead = null;
		function opb ($total, $onepage, $form_vars = '')
		{
			$page = &$_GET['page'];
			$this->total = &$total;
			$this->onepage = &$onepage;
			$this->total_page = ceil ($total / $onepage);
			if ($page == '')
			{
				$this->page = 1;
				$this->offset = 0;
			}
			else
			{
				$this->page = &$page;
				$this->offset = ($page - 1) * $onepage;
			}

			if ($form_vars != '')
			{
				$vars = explode ('|', $form_vars);
				$chk = $vars[0];
				$chk2 = $vars[1];
				$chk_value = &$_POST['' . $chk];
				$chk_value2 = &$_POST['' . $chk2];
				if (($chk_value == '' AND $chk_value2 == ''))
				{
					$formlink = '';
				}
				else
				{
					for ($i = 0; $i < sizeof ($vars); ++$i)
					{
						$var = $vars[$i];
						$value = &$_POST['' . $var];
						$addchar = $var . '=' . urlencode ($value);
						$formlink = $formlink . $addchar . '&';
					}
				}
			}
			else
			{
				$formlink = '';
			}

			$linkarr = explode ('page=', $_SERVER['QUERY_STRING']);
			$linkft = $linkarr[0];
			if ($linkft == '')
			{
				$this->linkhead = $_SERVER['PHP_SELF'] . '?' . $formlink;
			}
			else
			{
				$linkft = (substr ($linkft, -1) == '&' ? $linkft : $linkft . '&');
				$this->linkhead = $_SERVER['PHP_SELF'] . '?' . $linkft . $formlink;
			}

		}

		function offset ()
		{
			return $this->offset;
		}

		function first_page ($link = '', $char = '', $color = '')
		{
			$linkhead = &$this->linkhead;
			$linkchar = ($char == '' ? '' . '<font color=\'' . $color . '\'>[1]</font>' : $char);
			if ($link == 1)
			{
				return '' . '<a href="' . $linkhead . ('' . 'page=1" title="The first page">' . $linkchar . '</a>');
			}
			else
			{
				return 1;
			}

		}

		function total_page ($link = '', $char = '', $color = '')
		{
			$linkhead = &$this->linkhead;
			$total_page = &$this->total_page;
			$linkchar = ($char == '' ? '' . '<font color=\'' . $color . '\'>[' . $total_page . ']</font>' : $char);
			if ($link == 1)
			{
				return '' . '<a href="' . $linkhead . ('' . 'page=' . $total_page . '" title="The lasted page">' . $linkchar . '</a>');
			}
			else
			{
				return $total_page;
			}

		}

		function pre_page ($char = '')
		{
			$linkhead = &$this->linkhead;
			$page = &$this->page;
			if ($char == '')
			{
				$char = '[<]';
			}

			if (1 < $page)
			{
				$pre_page = $page - 1;
				return '' . '<a href="' . $linkhead . ('' . 'page=' . $pre_page . '" title="previous page">' . $char . '</a>');
			}
			else
			{
				return '';
			}

		}

		function next_page ($char = '')
		{
			$linkhead = &$this->linkhead;
			$total_page = &$this->total_page;
			$page = &$this->page;
			if ($char == '')
			{
				$char = '[>]';
			}

			if ($page < $total_page)
			{
				$next_page = $page + 1;
				return '' . '<a href="' . $linkhead . ('' . 'page=' . $next_page . '" title="next page">' . $char . '</a>');
			}
			else
			{
				return '';
			}

		}

		function num_bar ($num = '', $color = '', $maincolor = '', $left = '', $right = '')
		{
			$num = ($num == '' ? 8 : $num);
			$this->num = &$num;
			$mid = floor ($num / 2);
			$last = $num - 1;
			$page = &$this->page;
			$totalpage = &$this->total_page;
			$linkhead = &$this->linkhead;
			$left = ($left == '' ? '[' : $left);
			$right = ($right == '' ? ']' : $right);
			$color = ($color == '' ? '#ff0000' : $color);
			$minpage = ($page - $mid < 1 ? 1 : $page - $mid);
			$maxpage = $minpage + $last;
			if ($totalpage < $maxpage)
			{
				$maxpage = &$totalpage;
				$minpage = $maxpage - $last;
				$minpage = ($minpage < 1 ? 1 : $minpage);
			}

			for ($i = $minpage; $i <= $maxpage; ++$i)
			{
				$chars = $left . $i . $right;
				$char = '' . '<font color=\'' . $maincolor . '\'>' . $chars . '</font>';
				if ($i == $page)
				{
					$char = '' . '<font color=\'' . $color . '\'>' . $chars . '</font>';
				}

				$linkchar = '' . '<a href=\'' . $linkhead . ('' . 'page=' . $i . '\'>') . $char . '</a>';
				$linkbar .= $linkchar;
			}

			return $linkbar;
		}

		function pre_group ($char = '')
		{
			$page = &$this->page;
			$linkhead = &$this->linkhead;
			$num = &$this->num;
			$mid = floor ($num / 2);
			$minpage = ($page - $mid < 1 ? 1 : $page - $mid);
			$char = ($char == '' ? '[<<]' : $char);
			$pgpage = ($num < $minpage ? $minpage - $mid : 1);
			return '' . '<a href=\'' . $linkhead . ('' . 'page=' . $pgpage . '\' title="previous group number bar">') . $char . '</a>';
		}

		function next_group ($char = '')
		{
			$page = &$this->page;
			$linkhead = &$this->linkhead;
			$totalpage = &$this->total_page;
			$num = &$this->num;
			$mid = floor ($num / 2);
			$last = $num;
			$minpage = ($page - $mid < 1 ? 1 : $page - $mid);
			$maxpage = $minpage + $last;
			if ($totalpage < $maxpage)
			{
				$maxpage = &$totalpage;
				$minpage = $maxpage - $last;
				$minpage = ($minpage < 1 ? 1 : $minpage);
			}

			$char = ($char == '' ? '[>>]' : $char);
			$ngpage = ($maxpage + $last < $totalpage ? $maxpage + $mid : $totalpage);
			return '' . '<a href=\'' . $linkhead . ('' . 'page=' . $ngpage . '\' title="next group number bar">') . $char . '</a>';
		}

		function whole_num_bar ($num = '', $color = '', $maincolor = '')
		{
			$num_bar = $this->num_bar ($num, $color, $maincolor);
			return $this->first_page (1, '', $maincolor) . $this->pre_group ('' . '<font color="' . $maincolor . '">[<<]</font>') . $this->pre_page ('' . '<font color="' . $maincolor . '">[<]</font>') . $num_bar . $this->next_page ('' . '<font color="' . $maincolor . '">[>]</font>') . $this->next_group ('' . '<font color="' . $maincolor . '">[>>]</font>') . $this->total_page (1, '', $maincolor);
		}

		function whole_bar ($jump = '', $num = '', $color = '', $maincolor = '')
		{
			$whole_num_bar = $this->whole_num_bar ($num, $color, $maincolor) . '&nbsp;';
			$jump_form = $this->jump_form ($jump);
			return ' <table width="100%" border="0" cellspacing="0" cellpadding="0">
	 <tr>
' . ('' . '			<td align="right">' . $whole_num_bar . '</td>
') . ('' . '			<td width="50" align="right">' . $jump_form . '</td>
') . '	 </tr>
' . ' </table>
';
		}

		function jump_form ($jump = '')
		{
			$formname = 'pagebarjumpform' . $jump;
			$jumpname = 'jump' . $jump;
			$linkhead = $this->linkhead;
			$total = $this->total_page;
			return '<table width="60" border="0" cellspacing="0" cellpadding="0">
<script language="javascript">
' . ('' . '		function ' . $jumpname . '(linkhead, total, page)
') . '		{
' . '				var page = (page.value>total)?total:page.value;
' . '				page		 = (page<1)? 1 : page; 
' . '				location.href = linkhead + "page=" + page;
' . '				return false;
' . '		}
' . '</script>
' . ('' . '<form name="' . $formname . '" method="post" onSubmit="return ' . $jumpname . '(\'' . $linkhead . '\', ' . $total . ', ' . $formname . '.page)">
') . '	<tr>
' . '		<td>
' . '			 <input name="page" type="text" size="1">
' . ('' . '			 <input type="button" name="Submit" value="GO" onClick="return ' . $jumpname . '(\'' . $linkhead . '\', ' . $total . ', ' . $formname . '.page)">
') . '		</td>
' . '	</tr>
' . '</form>
' . '</table>
';
		}
	}


?>
