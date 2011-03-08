<?
	function utime ()
	{
		$time = explode (' ', microtime ());
		$usec = (double)$time[0];
		$sec = (double)$time[1];
		return $usec + $sec;
	}

	function import ($mod)
	{
		include_once 'modules/' . $mod . '.php';
	}

	function error ($msg, $history = '-1')
	{
		echo '<script language=javascript>';
		echo '' . 'window.alert(\'' . $msg . '\');';
		echo 'history.go(' . $history . ');';
		echo '</script>';
		exit ();
	}

	function errortest ($msg, $action = '-1', $mode = 'back')
	{
		echo '<script language=javascript>';
		echo '' . 'window.alert(\'' . $msg . '\');';
		if ($mode == 'back')
		{
			echo 'history.go(' . $history . ');';
		}
		else
		{
			echo 'window.close';
		}

		echo '</script>';
		exit ();
	}

	function showloading ($url, $msg = '数据采集中...', $contents = '这会根据您的网络速度以及目标站的网络速度来决定消耗时间.请耐心等待!', $waitTimes = 1)
	{
		echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
		echo '<meta http-equiv="refresh" content="' . $waitTimes . ';URL=' . $url . '">';
		echo '<link rel="stylesheet" href="images/style.css" type="text/css" />';
		echo '<script type="text/javascript" src="images/js/loadbar.js"></script>
';
		echo '<body onload="loadBar(1)">
';
		echo '<br><br><br><br><br><br>';
		echo '<div id="loader"><center><table style="FILTER: Alpha(opacity=75);" class="loader" summary="Loader Layout" border="0" cellpadding="5" cellspacing="1" bgcolor="#bbbbb">
';
		echo '<tr>
';
		echo '<td bgcolor="#FFFFFF" align="left"><p>
';
		echo '<img src="images/loading.gif" height="32" width="32" align="left" style="margin:3px" alt="请等待"/><strong>' . $msg . '</strong><br /> <span style="font-size:8pt;">' . $contents . '&nbsp;&nbsp;</span></p></td>' . '
';
		echo '</tr>
';
		echo '</table></center></div><br>
';
	}

	function deletehtml ($scr)
	{
		$l = strlen ($scr);
		for ($i = 0; $i < $l; ++$i)
		{
			if (substr ($scr, $i, 1) == '<')
			{
				$ii = $i;
				while ((substr ($scr, $i, 1) != '>' AND $i < $l))
				{
					++$i;
				}

				if ($i == $l)
				{
					$i = $ii - 1;
					$b = 1;
				}

				++$i;
			}

			if ((substr ($scr, $i, 1) != '<' OR $b == 1))
			{
				$str = $str . substr ($scr, $i, 1);
				continue;
			}
			else
			{
				--$i;
				continue;
			}
		}

		return $str;
	}

	function html2text ($string)
	{
		$string = str_replace ('&', '&amp;', $string);
		$string = str_replace ('"', '&quot;', $string);
		$string = str_replace ('<', '&lt;', $string);
		$string = str_replace ('>', '&gt;', $string);
		return $string;
	}

	function gbk_to_utf8($string)
	{
		iconv('gbk','utf-8//IGNORE',$string);
		return $string;
	}
	function utf8_to_gbk($string)
	{
		iconv('utf-8','gbk//IGNORE',$string);
		return $string;
	}
	function text2html ($string)
	{
		$string = str_replace ('&amp;', '&', $string);
		$string = str_replace ('&quot;', '"', $string);
		$string = str_replace ('&lt;', '<', $string);
		$string = str_replace ('&gt;', '>', $string);
		return $string;
	}

	function p ($array)
	{
		echo '<pre>';
		print_r ($array);
		echo '</pre><hr>';
	}

	function c_substr ($str, $start = 0, $end = 20)
	{
		$ch = chr (127);
		$p = array ('/[x81-xfe]([x81-xfe]|[x40-xfe])/', '/[x01-x77]/');
		$r = array ('', '');
		if (2 < func_num_args ())
		{
			$end = func_get_arg (2);
		}
		else
		{
			$end = strlen ($str);
		}

		if ($start < 0)
		{
			$start += $end;
		}

		if (0 < $start)
		{
			$s = substr ($str, 0, $start);
			if ($ch < $s[strlen ($s) - 1])
			{
				$s = preg_replace ($p, $r, $s);
				$start += strlen ($s);
			}
		}

		$s = substr ($str, $start, $end);
		$end = strlen ($s);
		if ($ch < $s[$end - 1])
		{
			$s = preg_replace ($p, $r, $s);
			$end += strlen ($s);
		}

		return substr ($str, $start, $end);
	}

	function m_substr ($str, $start = 0, $end = 20)
	{
		preg_match_all ('/[x80-xff]?./', $str, $ar);
		if (3 <= func_num_args ())
		{
			$end = func_get_arg (2);
			return join ('', array_slice ($ar[0], $start, $end));
		}
		else
		{
			return join ('', array_slice ($ar[0], $start));
		}

	}

	function getextension ($path)
	{
		return strtolower (substr (strrchr ($path, '.'), 1));
	}

	function stripslashes_array ($array)
	{
		reset ($array);
		while ($pair = each ($array))
		{
			if (is_array ($pair[1]))
			{
				$array[$pair[0]] = stripslashes_array ($pair[1]);
				continue;
			}
			else
			{
				$array[$pair[0]] = addslashes ($pair[1]);
				continue;
			}
		}

		return $array;
	}
?>
