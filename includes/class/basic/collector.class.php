<?

  class neat_collector
  {
    var $contents = null;
    var $getTagFromAreaMatch = array ();
    var $getFailed = null;
    function settag ($tag)
    {
      $this->tag = $tag;
    }

    function settagpattern ($tagPattern)
    {
      $this->tagPattern = $tagPattern;
    }

    function setarea ($area)
    {
      $this->area = $area;
    }

    function setcontents ($contents)
    {
      $this->contents = $contents;
    }

    function getcontents ($url)
    {
      if (function_exists ('file_get_contents'))
      {
        $this->contents = @file_get_contents ($url);
      }
      else
      {
        $this->contents = join ('', @file ($url));
      }

      if ($this->contents)
      {
        return $this->contents;
      }
      else
      {
        ++$this->getFailed;
        return false;
      }

    }

    function getcontentsbyfsockopen ($url, $method = 'GET', $param = '', $referer, $useragent, $replaceRNT)
    {
      $tmp = parse_url (trim ($url));
      $tmp['query'] = ($tmp['query'] ? '?' . $tmp['query'] : '');
      $tmp['port'] = ($tmp['port'] ? $tmp['port'] : '80');
      if (!$tmp['path'])
      {
        $tmp['path'] .= '/';
      }

      $sp = @fsockopen ($tmp['host'], $tmp['port'], &$errno, &$errstr);
      if (!$sp)
      {
        ++$this->getFailed;
        return false;
      }
      else
      {
        if ($method == 'GET')
        {
          $command = "GET " . $tmp['path'] . $tmp['query'] . " HTTP/1.0\r\n";
			if (!$referer == '')
			{
				$command .= "Referer: " . $referer . "\r\n";
			}
			if (!$useragent == '')
			{
				$command .= "User-Agent: " . $useragent . "\r\n";
			}

          $command .= "Host: " . $tmp['host'] . "\r\n";
          ($param['cookie'] != '' ? $command .= "Cookie: " . $param['cookie'] . "\r\n" : null);
          $command .= "Connection: close\r\n\r\n";
        }
        else
        {
          if ($method == 'POST')
          {
            $command = "POST " . $tmp['path'] . $tmp['query'] . " HTTP/1.1\r\n";
			if (!$referer == '')
			{
				$command .= "Referer: " . $referer . "\r\n";
			}
			if (!$useragent == '')
			{
				$command .= "User-Agent: " . $useragent . "\r\n";
			}
            $command .= "Host: " . $tmp['host'] . "\r\n";
            $command .= "ontent-type: application/x-www-form-urlencoded\r\n";
            $command .= "Content-length: " . strlen ($param['post']) . "\r\n";
            ($param['cookie'] != '' ? $command .= "Cookie: " . $param['cookie'] . "\r\n" : null);
            $command .= "Connection: close\r\n\r\n";
            $command .= $param['post'];
          }
          else
          {
            return false;
          }
        }

        fwrite ($sp, $command);
        $buffer = "\r\n";
        while ($buffer)
        {
          $buffer = fgets ($sp, 10240);
          $buffer = trim ($buffer);
          $header .= $buffer;
        }

        while (!feof ($sp))
        {
          $contents .= fgets ($sp, 10240);
        }

        fclose ($sp);
        $this->contents = $contents;
		//分类数组,得到空格,回车,TAB,调试.然后过滤空格,回车,TAB,是否显示调试信息.
      			$replaceRNT = @explode(',', $replaceRNT );
//			var_dump($replaceRNT);die();
			if ($replaceRNT[4] != 1)
			{
				$this->contents = iconv('GBK','utf8//IGNORE',$contents);
			}
			else
			{
				$this->contents = $contents;
			}
//			var_dump($this->contents);die();
		if ($replaceRNT[0] == 1)
		{
			$this->contents =str_replace("\r","",$this->contents);
		}
		if ($replaceRNT[1] == 1)
		{
			$this->contents =str_replace("\n","",$this->contents);
		}

		if ($replaceRNT[2] == 1)
		{
			$this->contents =str_replace("\t","",$this->contents);
		}
		//显示调试
		if ($replaceRNT[3] == 1)
		{
			echo "<SCRIPT>function runEx(){var winEx2 = window.open(\"\", \"winEx2\", \"width=500,height=300,status=yes,menubar=no,scrollbars=yes,resizable=yes\"); winEx2.document.open(\"text/html\", \"replace\"); winEx2.document.write(unescape(event.srcElement.parentElement.children[0].value)); winEx2.document.close(); }function saveFile(){var win=window.open('','','top=10000,left=10000');win.document.write(document.all.asdf.innerText);win.document.execCommand('SaveAs','','javascript.htm');win.close();}</SCRIPT><center><TEXTAREA id=asdf name=textfield rows=32  wrap=VIRTUAL cols=\"120\">".$this->contents."</TEXTAREA><BR><BR><INPUT name=Button onclick=runEx() type=button value=\"查看效果\">&nbsp;&nbsp;<INPUT name=Button onclick=asdf.select() type=button value=\"全选\">&nbsp;&nbsp;<INPUT name=Button onclick=\"asdf.value=''\" type=button value=\"清空\">&nbsp;&nbsp;<INPUT onclick=saveFile(); type=button value=\"保存代码\"></center>";
		}

        return $this->contents;
      }

    }

    function gettagfromarea ($key)
    {
      $this->areaAddSlashed[$key] = $this->addSlash ($this->area[$key]);
      $pattern = '/\\[(.+?)\\\\]/is';
      preg_match_all ($pattern, $this->areaAddSlashed[$key], $this->getTagFromAreaMatch[$key]);
      return $this->getTagFromAreaMatch[$key];
    }

    function gettagappeartimes ($key)
    {
      $this->tagAppearTimes[$key] = array_count_values ($this->getTagFromAreaMatch[$key][1]);
      return $this->tagAppearTimes[$key];
    }

    function gettagposition ($key)
    {
      foreach ($this->getTagFromAreaMatch[$key][1] as $k => $v)
      {
        if (in_array ('[' . $v . ']', $this->tag))
        {
          $thisKey = array_keys ($this->tag, '[' . $v . ']');
          $thisKey = $thisKey[0];
          if (1 < $this->tagAppearTimes[$key])
          {
            $this->tagPosition[$key][$thisKey][] = $k + 1;
            continue;
          }
          else
          {
            $this->tagPosition[$key][$thisKey] = $k + 1;
            continue;
          }

          continue;
        }
      }

      return $this->tagPosition[$key];
    }

    function replacetag ($key)
    {
      $this->areaReplaced[$key] = $this->areaAddSlashed[$key];
      if (!empty ($this->tagPosition[$key]))
      {
        foreach ($this->tagPosition[$key] as $k => $v)
        {
          $tagPattern = $this->tagPattern[$k];
          if (!$tagPattern)
          {
            $tagPattern = '(.*?)';
          }

          $this->areaReplaced[$key] = str_replace ($this->addSlash ($this->tag[$k]), $tagPattern, $this->areaReplaced[$key]);
        }
      }

      return $this->finalMatch[$key];
    }

    function getmatchdata ($key, $type = 1, $format = 1)
    {
      $typeArray[1] = 'is';
      $typeArray[2] = 'i';
      $areaReplaced = $this->areaReplaced[$key];
      if ($format == 2)
      {
        $areaReplaced = $this->unixFormat ($areaReplaced);
      }

      preg_match_all ('/' . $areaReplaced . '/' . $typeArray[$type], $this->contents, $this->finalMatch[$key]);
	  //var_dump($this->finalMatch[$key]);
      return $this->finalMatch[$key];
    }

    function result ($key)
    {
      if (!empty ($this->tagPosition[$key]))
      {
        foreach ($this->tagPosition[$key] as $k => $v)
        {
          if (is_array ($this->tagPosition[$key][$k]))
          {
            foreach ($this->tagPosition[$key][$k] as $sk => $sv)
            {
              $this->result[$key][$k][] = $this->finalMatch[$key][$sv];
            }

            continue;
          }
          else
          {
            $this->result[$key][$k] = $this->finalMatch[$key][$v];
            continue;
          }
        }
      }

      return $this->result[$key];
    }

    function pickup ($key, $type = 1, $format = 1)
    {
      $this->getTagFromArea ($key);
      $this->getTagAppearTimes ($key);
      $this->getTagPosition ($key);
      $this->replaceTag ($key);
      $this->getMatchData ($key, $type, $format);
      return $this->result ($key);
    }

    function getreplacedarea ($key)
    {
      $this->getTagFromArea ($key);
      $this->getTagAppearTimes ($key);
      $this->getTagPosition ($key);
      $this->replaceTag ($key);
      return $this->areaReplaced[$key];
    }

    function unixformat ($contents)
    {
      $contents = str_replace ("\r\n", "\n", $contents);
      return $contents;
    }

    function addslash ($contents)
    {
      $char = array ('\\', '[', ']', '{', '}', '(', ')', '*', '+', '.', ('' . '$'), '?', '^', '/', '|');
      $charFix = array ('\\\\', '\\[', '\\]', '\\{', '\\}', '\\(', '\\)', '\\*', '\\+', '\\.', '$', '\\?', '\\^', '\\/', '\\|');
      $contents = str_replace ($char, $charFix, $contents);
      return $contents;
    }

    function setpageurl ($url)
    {
      $this->pageURL = $this->getPath ($url, 2, 0);
    }

    function getbaseurl ($contents)
    {
      if (!$contents)
      {
        return false;
      }

      preg_match ('/<base([^"\'<>]+)(href=("|\\\'|))([^"\'<> ]+)("|\\\'|)/is', $contents, $match);
      if ($match[4])
      {
        return $match[4];
      }
      else
      {
        return false;
      }

    }

    function geturltype ($url)
    {
      if (preg_match ('/^(https?|ftp|gopher|news|telnet|mms){1}:\\/\\//is', $url))
      {
        $type = 1;
      }
      else
      {
        if (preg_match ('/^\\//', $url))
        {
          $type = 2;
        }
        else
        {
          if (preg_match ('/^.\\//', $url))
          {
            $type = 3;
          }
          else
          {
            if (preg_match ('/^..\\//', $url))
            {
              $type = 4;
            }
            else
            {
              if (preg_match ('/^mailto:/is', $url))
              {
                $type = 5;
              }
              else
              {
                $type = 6;
              }
            }
          }
        }
      }

      return $type;
    }

    function getpath ($url, $type, $backLevel = 0)
    {
      $pathArray = explode ('/', $url);
      if ($type == 1)
      {
        for ($i = 0; $i < 3; ++$i)
        {
          $finalPath .= $pathArray[$i] . '/';
        }

        $strNum = strlen ($finalPath);
        $finalPath = substr ($finalPath, 0, $strNum - 1);
      }
      else
      {
        if ($type == 2)
        {
          $levelNum = count ($pathArray) - 1 - $backLevel;
          for ($i = 0; $i < $levelNum; ++$i)
          {
            $finalPath .= $pathArray[$i] . '/';
          }
        }
      }

      return $finalPath;
    }

    function fixpath ($url)
    {
      $url = str_replace ('\\', '/', $url);
      $type = $this->getURLType ($url);
      switch ($type)
      {
        case 1:
        {
          $path = '';
          break;
        }

        case 2:
        {
          $path = $this->getPath ($this->pageURL, 1);
          break;
        }

        case 3:
        {
          $path = $this->pageURL;
          $url = str_replace ('./', '', $url);
          break;
        }

        case 4:
        {
          $levelDeep = substr_count ($url, '../');
          $path = $this->getPath ($this->pageURL, 2, $levelDeep);
          $url = str_replace ('../', '', $url);
          break;
        }

        case 5:
        {
          $path = '';
          break;
        }

        case 6:
        {
          $path = $this->pageURL;
          break;
        }

        default:
        {
          $path = '';
          break;
        }
      }

      return $path . $url;
    }

    function debug ()
    {
      echo '<pre>';
      print_r ($this);
      echo '</pre>';
    }
  }

?>
