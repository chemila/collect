<?
	class neat_gd
	{
		var $ouputPath = './';
		var $thumbWidth = 100;
		var $thumbHeight = 100;
		var $thumbExt = '_thumb';
		var $markExt = '';
		var $fontSize = 12;
		var $font = 'simsun.ttc';
		var $imageQuality = 90;
		var $textBackgroundTransition = 80;
		var $imageTransition = 60;
		var $sourceImageObject = null;
		var $newImageObject = null;
		var $waterImageObject = null;
		function neat_gd ($path)
		{
			$this->setOutputPath ($path);
		}

		function setoutputpath ($path)
		{
			$this->ouputPath = $path;
		}

		function thumb ($imageFile)
		{
			$imageInfo = $this->getInfo ($imageFile);
			$outputThumbImageFile = $this->outputPath . $imageInfo['name'] . $this->thumbExt . '.jpg';
			$this->createSourceImageObject ($imageFile, $imageInfo['typeID']);
			$width = ($imageInfo['width'] < $this->thumbWidth ? $imageInfo['width'] : $this->thumbWidth);
			$height = ($imageInfo['height'] < $this->thumbHeight ? $imageInfo['height'] : $this->thumbHeight);
			$srcW = $imageInfo['width'];
			$srcH = $imageInfo['height'];
			($srcH * $height < $srcW * $width ? $height = round ($srcH * $width / $srcW) : $width = round ($srcW * $height / $srcH));
			$this->createNewImageObject ($width, $height, $imageInfo['width'], $imageInfo['height']);
			imagejpeg ($this->newImageObject, $outputThumbImageFile, $this->imageQuality);
			$this->destroySourceImageObject ();
			$this->destroyNewImageObject ();
		}

		function textmark ($imageFile, $text)
		{
			$imageInfo = $this->getInfo ($imageFile);
			if ($imageInfo['type'] != 'jpg')
			{
				return null;
			}

			$outputMarkImageFile = $this->outputPath . $imageInfo['name'] . $this->markExt . '.jpg';
			$this->createSourceImageObject ($imageFile, $imageInfo['typeID']);
			$width = $imageInfo['width'];
			$height = $imageInfo['height'];
			$white = imagecolorallocate ($this->sourceImageObject, 255, 255, 255);
			$black = imagecolorallocate ($this->sourceImageObject, 0, 0, 0);
			$alpha = imagecolorallocatealpha ($this->sourceImageObject, 230, 230, 230, $this->textBackgroundTransition);
			imagefilledrectangle ($this->sourceImageObject, 0, $height - 60, $width, $height - 20, $alpha);
			imageline ($this->sourceImageObject, 0, $height - 60, $width, $height - 60, $black);
			imageline ($this->sourceImageObject, 0, $height - 20, $width, $height - 20, $black);
			$fontWidth = imagettfbbox ($this->fontSize, 0, $this->font, $text);
			$fontWidthFinal = $fontWidth[2] - $fontWidth[6];
			$text = $text;
			imagettftext ($this->sourceImageObject, $this->fontSize, 0, $width - $fontWidthFinal - 30, $height - 35, $black, $this->font, $text);
			imagejpeg ($this->sourceImageObject, $outputMarkImageFile, $this->imageQuality);
			$this->destroySourceImageObject ();
		}

		function imagemark ($imageFile, $markFile)
		{
			$imageInfo = $this->getInfo ($imageFile);
			$imageWaterInfo = $this->getInfo ($markFile);
			if ($imageInfo['type'] != 'jpg')
			{
				return null;
			}

			$outputMarkImageFile = $this->outputPath . $imageInfo['name'] . $this->markExt . '.jpg';
			$this->createSourceImageObject ($imageFile, $imageInfo['typeID']);
			$this->createWaterImageObject ($markFile, $imageWaterInfo['typeID']);
			imagecopymerge ($this->sourceImageObject, $this->waterImageObject, $imageInfo['width'] - $imageWaterInfo['width'] - 30, $imageInfo['height'] - $imageWaterInfo['height'] - 30, 0, 0, $imageWaterInfo['width'], $imageWaterInfo['height'], $this->imageTransition);
			imagejpeg ($this->sourceImageObject, $outputMarkImageFile, $this->imageQuality);
			$this->destroyWaterImageObject ();
			$this->destroySourceImageObject ();
		}

		function getinfo ($imageFile)
		{
			$type[1] = 'gif';
			$type[2] = 'jpg';
			$type[3] = 'png';
			$imgData = getimagesize ($imageFile);
			$pos = strrpos ($imageFile, '.');
			$ext = substr ($imageFile, $pos + 1);
			$name = substr ($imageFile, 0, $pos);
			$imageInfo['name'] = $name;
			$imageInfo['ext'] = $ext;
			$imageInfo['typeID'] = $imgData[2];
			$imageInfo['type'] = $type[$imgData[2]];
			$imageInfo['file'] = basename ($imageFile);
			$imageInfo['size'] = filesize ($imageFile);
			$imageInfo['width'] = $imgData[0];
			$imageInfo['height'] = $imgData[1];
			return $imageInfo;
		}

		function createsourceimageobject ($imageFile, $type)
		{
			switch ($type)
			{
				case 1:
				{
					$this->sourceImageObject = imagecreatefromgif ($imageFile);
					break;
				}

				case 2:
				{
					$this->sourceImageObject = imagecreatefromjpeg ($imageFile);
					break;
				}

				case 3:
				{
					$this->sourceImageObject = imagecreatefrompng ($imageFile);
					break;
				}

				default:
				{
					return 0;
					break;
				}
			}

		}

		function createwaterimageobject ($imageFile, $type)
		{
			switch ($type)
			{
				case 1:
				{
					$this->waterImageObject = imagecreatefromgif ($imageFile);
					break;
				}

				case 2:
				{
					$this->waterImageObject = imagecreatefromjpeg ($imageFile);
					break;
				}

				case 3:
				{
					$this->waterImageObject = imagecreatefrompng ($imageFile);
					break;
				}

				default:
				{
					return 0;
					break;
				}
			}

		}

		function createnewimageobject ($width, $height, $f_width, $f_height)
		{
			if (function_exists ('imagecreatetruecolor'))
			{
				$this->newImageObject = imagecreatetruecolor ($width, $height);
				imagecopyresampled ($this->newImageObject, $this->sourceImageObject, 0, 0, 0, 0, $width, $height, $f_width, $f_height);
			}
			else
			{
				$this->newImageObject = imagecreate ($width, $height);
				imagecopyresized ($this->newImageObject, $this->sourceImageObject, 0, 0, 0, 0, $width, $height, $f_width, $f_height);
			}

		}

		function destroysourceimageobject ()
		{
			imagedestroy ($this->sourceImageObject);
		}

		function destroynewimageobject ()
		{
			imagedestroy ($this->newImageObject);
		}

		function destroywaterimageobject ()
		{
			imagedestroy ($this->waterImageObject);
		}

		function gb2utf8 ($gb)
		{
			if (!trim ($gb))
			{
				return $gb;
			}

			$filename = 'gb2312.txt';
			$tmp = file ($filename);
			$codetable = array ();
			while (list ($key, $value) = each ($tmp))
			{
				$codetable[hexdec (substr ($value, 0, 6))] = substr ($value, 7, 6);
			}

			$ret = '';
			$utf8 = '';
			while ($gb)
			{
				if (127 < ord (substr ($gb, 0, 1)))
				{
					$thischr = substr ($gb, 0, 2);
					$gb = substr ($gb, 2, strlen ($gb));
					$utf8 = $this->u2utf8 (hexdec ($codetable[hexdec (bin2hex ($thischr)) - 32896]));
					for ($i = 0; $i < strlen ($utf8); $i += 3)
					{
						$ret .= chr (substr ($utf8, $i, 3));
					}

					continue;
				}

				$ret .= substr ($gb, 0, 1);
				$gb = substr ($gb, 1, strlen ($gb));
			}

			return $ret;
		}

		function u2utf8 ($c)
		{
			for ($i = 0; $i < count ($c); ++$i)
			{
				$str = '';
			}

			if ($c < 128)
			{
				$str .= $c;
			}
			else
			{
				if ($c < 2048)
				{
					$str .= 192 | $c >> 6;
					$str .= 128 | $c & 63;
				}
				else
				{
					if ($c < 65536)
					{
						$str .= 224 | $c >> 12;
						$str .= 128 | $c >> 6 & 63;
						$str .= 128 | $c & 63;
					}
					else
					{
						if ($c < 2097152)
						{
							$str .= 240 | $c >> 18;
							$str .= 128 | $c >> 12 & 63;
							$str .= 128 | $c >> 6 & 63;
							$str .= 128 | $c & 63;
						}
					}
				}
			}

			return $str;
		}
	}


?>
