<?
	class neat_processhttpimg
	{
		var $contents = null;
		var $savePath = null;
		var $allowType = array ();
		var $imgUrlArray = array ();
		var $failSaveArray = array ();
		var $savedUrl = array ();
		function neat_processhttpimg ($path)
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

		function setallowtype ($type = array (0 => 'gif', 1 => 'jpg', 2 => 'jpeg', 3 => 'png', 4 => 'bmp'))
		{
			$this->allowType = $type;
		}

		function getimgurl ()
		{
			preg_match_all ('' . '/(<img(.+?)src=("|\\\'|))([a-z0-9\\/\\-_+=.~!%@?#%&;:$\\│]+(.bmp|.gif|.jpg|.jpeg|.png))("|\\\'|)/is', $this->contents, $match);
			$this->imgUrlArray = $match[4];
			return $this->imgUrlArray;
		}

		function replaceurl ($url, $newUrl)
		{
			$this->contents = str_replace ($url, $newUrl, $this->contents);
		}

		function getimg ($url)
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

		function writeimgfile ($path, $content, $method = 'wb')
		{
			$handle = @fopen ($path, $method);
			@fwrite ($handle, $content);
			@fclose ($handle);
		}

		function getextension ($path)
		{
			return strtolower (substr (strrchr ($path, '.'), 1));
		}

		function getimginfo ($imageFile)
		{
			$type[1] = 'gif';
			$type[2] = 'jpg';
			$type[3] = 'png';
			$type[6] = 'bmp';
			$imgData = getimagesize ($imageFile);
			$imageInfo['width'] = $imgData[0];
			$imageInfo['height'] = $imgData[1];
			$imageInfo['type'] = $type[$imgData[2]];
			return $imageInfo;
		}

		function saveimg ($array, $maxSize = 0, $minSize = 0)
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
					$imgContent = $this->getImg ($remotePath);
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
						$this->writeImgFile ($localPath, $imgContent);
						$imgData = $this->getImgInfo ($localPath);
						if (!$imgData)
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
