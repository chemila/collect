<?
	class neat_processhttpswf
	{
		var $contents = null;
		var $savePath = null;
		var $allowType = array ();
		var $swfUrlArray = array ();
		var $failSaveArray = array ();
		var $savedUrl = array ();
		function neat_processhttpswf ($path)
		{
			$this->setSavePath ($path);
		}

		function setsavepath ($path)
		{
			$this->savePath = $path;
		}

		function setcontents ($str)
		{
			$this->contents = $str;
		}

		function setallowtype ($type = array (0 => 'swf'))
		{
			$this->allowType = $type;
		}

		function getswfurl ()
		{
			preg_match_all ('' . '\'<embed.*?src=("|\\\')([a-z0-9\\/\\-_+=.~!%@?#%&;:$\\│]+)("|\\\')\'si', $this->contents, $match);
			$this->swfUrlArray = $match[2];
			return $this->swfUrlArray;
		}

		function replaceurl ($url, $newUrl)
		{
			$this->contents = str_replace ($url, $newUrl, $this->contents);
		}

		function getswf ($url)
		{
			if (function_exists ('file_get_contents'))
			{
				return file_get_contents ($url);
			}
			else
			{
				return join ('', file ($url));
			}

		}

		function writeswffile ($path, $content, $method = 'wb')
		{
			$handle = @fopen ($path, $method);
			@fwrite ($handle, $content);
			@fclose ($handle);
		}

		function getextension ($path)
		{
			return strtolower (substr (strrchr ($path, '.'), 1));
		}

		function getswfinfo ($swfFile)
		{
			$type[4] = 'swf';
			$swfData = getimagesize ($swfFile);
			$swfInfo['width'] = $swfData[0];
			$swfInfo['height'] = $swfData[1];
			$swfInfo['type'] = $type[$swfData[2]];
			return $swfInfo;
		}

		function saveswf ($array, $maxSize = 0, $minSize = 0)
		{
			if ((!is_array ($array) OR empty ($array)))
			{
				return false;
			}

			$i = 0;
			foreach ($array as $v)
			{
				if (in_array ($v, $this->savedUrl))
				{
					continue;
				}
				else
				{
					$remotePath = trim ($v);
					$imgContent = $this->getSwf ($remotePath);
					$imgSize = strlen ($imgContent);
					if ($maxSize != 0)
					{
						if ($maxSize * 1024 < $imgSize)
						{
							$this->failSaveArray[] = $v;
							continue;
						}
					}

					if ($minSize != 0)
					{
						if ($imgSize < $minSize * 1024)
						{
							$this->failSaveArray[] = $v;
							continue;
						}
					}

					$extension = $this->getExtension ($remotePath);
					$localName = time () . '_' . mt_rand () . '.' . $extension;
					$localPath = $this->savePath . $localName;
					if (($imgContent AND in_array ($extension, $this->allowType)))
					{
						$this->writeSwfFile ($localPath, $imgContent);
						$swfData = $this->getSwfInfo ($localPath);
						if (!$swfData)
						{
							@unlink ($localPath);
							$this->failSaveArray[] = $v;
						}
						else
						{
							$this->savedUrl[] = $v;
							$this->saved['remotePath'][$v] = $localName;
							$this->saved[$i]['localPath'] = $localPath;
							$this->saved[$i]['remotePath'] = $v;
							$this->saved['localFileName'][] = $localName;
						}
					}
					else
					{
						$this->failSaveArray[] = $v;
					}

					++$i;
					continue;
				}
			}

		}

		function debug ()
		{
			echo '<pre>';
			print_r ($this);
			echo '</pre>';
		}
	}

?>
