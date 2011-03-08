<?

	class ns_serialhandling_hlw2004
	{
		var $serverInfoMarkString = 'NS.NC.WGH.1-0-0';
		var $serverSNMarkString = 'NS-NC-14-WGH-YC';
		var $localInfoMarkString = 'FUCKYOU';
		var $localSNMarkString = 'FUCKYOU_AGAIN';
		var $ralphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890\\!,.:;?~@#$%^&*()_+-=][}{/><" ';
		function _asc2bin ($str)
		{
			$text_array = explode ('
', chunk_split ($str, 1));
			for ($n = 0; $n < count ($text_array) - 1; ++$n)
			{
				$newstring .= substr ('0000' . base_convert (ord ($text_array[$n]), 10, 2), -8);
			}

			$newstring = chunk_split ($newstring, 8, '');
			return $newstring;
		}

		function _bin2asc ($str)
		{
			$str = str_replace (' ', '', $str);
			$text_array = explode ('
', chunk_split ($str, 8));
			for ($n = 0; $n < count ($text_array) - 1; ++$n)
			{
				$newstring .= chr (base_convert ($text_array[$n], 2, 10));
			}

			return $newstring;
		}

		function _encrypt ($password, $strtoencrypt)
		{
			$ralphabet = &$this->ralphabet;
			$alphabet = $ralphabet . $ralphabet;
			for ($i = 0; $i < strlen ($password); ++$i)
			{
				$cur_pswd_ltr = substr ($password, $i, 1);
				$pos_alpha_ary[] = substr (strstr ($alphabet, $cur_pswd_ltr), 0, strlen ($ralphabet));
			}

			$i = 0;
			$n = 0;
			$nn = strlen ($password);
			$c = strlen ($strtoencrypt);
			while ($i < $c)
			{
				$encrypted_string .= substr ($pos_alpha_ary[$n], strpos ($ralphabet, substr ($strtoencrypt, $i, 1)), 1);
				++$n;
				if ($n == $nn)
				{
					$n = 0;
				}

				++$i;
			}

			return $encrypted_string;
		}

		function _decrypt ($password, $strtodecrypt)
		{
			$ralphabet = &$this->ralphabet;
			$alphabet = $ralphabet . $ralphabet;
			for ($i = 0; $i < strlen ($password); ++$i)
			{
				$cur_pswd_ltr = substr ($password, $i, 1);
				$pos_alpha_ary[] = substr (strstr ($alphabet, $cur_pswd_ltr), 0, strlen ($ralphabet));
			}

			$i = 0;
			$n = 0;
			$nn = strlen ($password);
			$c = strlen ($strtodecrypt);
			while ($i < $c)
			{
				$decrypted_string .= substr ($ralphabet, strpos ($pos_alpha_ary[$n], substr ($strtodecrypt, $i, 1)), 1);
				++$n;
				if ($n == $nn)
				{
					$n = 0;
				}

				++$i;
			}

			return $decrypted_string;
		}

		function _infoencode ($mask, $info)
		{
			$licenseString = serialize ($info);
			$licenseString = base64_encode ($licenseString);
			$licenseString = $this->_encrypt ($mask, $licenseString);
			$licenseString = base64_encode ($licenseString);
			$licenseString = $this->_asc2bin ($licenseString);
			$licenseString = str_replace ('1', '2', $licenseString);
			$licenseString = str_replace ('0', '1', $licenseString);
			$licenseString = str_replace ('2', '0', $licenseString);
			return $licenseString;
		}

		function _infodecode ($mask, $info)
		{
			$licenseString = str_replace ('1', '2', $info);
			$licenseString = str_replace ('0', '1', $licenseString);
			$licenseString = str_replace ('2', '0', $licenseString);
			$licenseString = $this->_bin2asc ($licenseString);
			$licenseString = base64_decode ($licenseString);
			$licenseString = $this->_decrypt ($mask, $licenseString);
			$licenseString = base64_decode ($licenseString);
			$info = unserialize ($licenseString);
			return $info;
		}

		function serverinfoencode ($info)
		{
			return $this->_infoEncode ($this->serverInfoMarkString, $info);
		}

		function serverinfodecode ($info)
		{
			return $this->_infoDecode ($this->serverInfoMarkString, $info);
		}

		function localinfoencode ($info)
		{
			return $this->_infoEncode ($this->localInfoMarkString, $info);
		}

		function localinfodecode ($info)
		{
			return $this->_infoDecode ($this->localInfoMarkString, $info);
		}

		function _createfinalsn ($MD5)
		{
			$SN[1][1] = 1;
			$SN[1][2] = 31;
			$SN[1][3] = 14;
			$SN[1][4] = 9;
			$SN[2][1] = 12;
			$SN[2][2] = 26;
			$SN[2][3] = 15;
			$SN[2][4] = 22;
			$SN[3][1] = 8;
			$SN[3][2] = 2;
			$SN[3][3] = 11;
			$SN[3][4] = 4;
			$SN[4][1] = 21;
			$SN[4][2] = 17;
			$SN[4][3] = 13;
			$SN[4][4] = 18;
			$SN[5][1] = 7;
			$SN[5][2] = 19;
			$SN[5][3] = 27;
			$SN[5][4] = 6;
			$SN[6][1] = 3;
			$SN[6][2] = 23;
			$SN[6][3] = 16;
			$SN[6][4] = 10;
			$FSN1 = $MD5[$SN[1][1]] . $MD5[$SN[1][2]] . $MD5[$SN[1][3]] . $MD5[$SN[1][4]];
			$FSN2 = $MD5[$SN[2][1]] . $MD5[$SN[2][2]] . $MD5[$SN[2][3]] . $MD5[$SN[2][4]];
			$FSN3 = $MD5[$SN[3][1]] . $MD5[$SN[3][2]] . $MD5[$SN[3][3]] . $MD5[$SN[3][4]];
			$FSN4 = $MD5[$SN[4][1]] . $MD5[$SN[4][2]] . $MD5[$SN[4][3]] . $MD5[$SN[4][4]];
			$FSN5 = $MD5[$SN[5][1]] . $MD5[$SN[5][2]] . $MD5[$SN[5][3]] . $MD5[$SN[5][4]];
			$FSN6 = $MD5[$SN[6][1]] . $MD5[$SN[6][2]] . $MD5[$SN[6][3]] . $MD5[$SN[6][4]];
			return $FSN1 . '-' . $FSN2 . '-' . $FSN3 . '-' . $FSN4 . '-' . $FSN5 . '-' . $FSN6;
		}

		function makeserversn ($type, $info)
		{
			$SERVER_SN = md5 ($this->serverSNMarkString . $type . $info['USER'] . $info['DOMAIN'] . $info['HTTP_HOST'] . $info['SERVER_ADDR']);
			$SERVER_SN = strtoupper ($SERVER_SN);
			$SERVER_SN = $this->_createFinalSN ($SERVER_SN);
			return $SERVER_SN;
		}

		function makelocalsn ($info)
		{
			$LOCAL_SN = serialize ($info);
			$LOCAL_SN = md5 ($this->localSNMarkString . $LOCAL_SN);
			$LOCAL_SN = strtoupper ($LOCAL_SN);
			$LOCAL_SN = $this->_createFinalSN ($LOCAL_SN);
			return $LOCAL_SN;
		}
	}

	define ('NS_Serialhandling_HLW2004_CLASS_IN_014', 1);
?>
