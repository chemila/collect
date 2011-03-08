<?
	class getlocalinfo
	{
		var $getModify = false;
		var $dirFilter = array ();
		function checkfun ($funName)
		{
			return function_exists ($funName);
		}

		function getserverapi ()
		{
			return strtoupper (php_sapi_name ());
		}

		function getserversoftware ()
		{
			$info['description'] = $_SERVER['SERVER_SOFTWARE'];
			$serverSoftware = strtolower (substr ($_SERVER['SERVER_SOFTWARE'], 0, 3));
			if ($serverSoftware == 'apa')
			{
				$info['type'] = 'APACHE';
			}
			else
			{
				if ($serverSoftware == 'mic')
				{
					$info['type'] = 'IIS';
				}
			}

			return $info;
		}

		function getosversion ()
		{
			return php_uname ();
		}

		function getmacaddress ()
		{
			if ($_SESSION['MAC_ADDRESS'])
			{
				$ipConfigInfo = $_SESSION['MAC_ADDRESS'];
			}
			else
			{
				@exec ('ipconfig /all', $infoArray);
				for ($i = 0; $i < count ($infoArray); ++$i)
				{
					if (eregi ('Description', $infoArray[$i]))
					{
						$tmpInfo = explode (':', $infoArray[$i]);
						$description[] = $tmpInfo[1];
					}

					if (eregi ('Physical', $infoArray[$i]))
					{
						$tmpInfo = explode (':', $infoArray[$i]);
						$macAdress[] = $tmpInfo[1];
						continue;
					}
				}

				$ipConfigInfo['description'] = $description;
				$ipConfigInfo['mac'] = $macAdress;
				$_SESSION['MAC_ADDRESS'] = $ipConfigInfo;
			}

			return $ipConfigInfo;
		}

		function getallfilectime ($path = './')
		{
			if (!$path)
			{
				return false;
			}

			$dir = opendir ($path);
			$dirName = $this->getDirName ($path);
			while (false !== $list = readdir ($dir))
			{
				$tmpArray['dir'];
				$tmpArray['file'];
				if (($list != '.' AND $list != '..'))
				{
					if (is_dir ($path . $list))
					{
						if (!in_array ($list, $this->dirFilter))
						{
							$tmpArray['dir'][] = $this->getAllFileCTime ($path . $list . '/');
							continue;
						}

						continue;
					}

					$tmp['file'] = $list;
					$tmp['created'] = @filectime ($path . $list);
					$tmp['created_format'] = @date ('Y-m-d H:i:s', $tmp['created']);
					if ($this->getModify == true)
					{
						$tmp['modify'] = @filemtime ($path . $list);
						$tmp['modify_format'] = @date ('Y-m-d H:i:s', $tmp['modify']);
					}

					$tmpArray['file'][] = $tmp;
					continue;
				}
			}

			$finalArray['name'] = $dirName;
			$finalArray['list'] = $tmpArray;
			$array = $finalArray;
			return $array;
		}

		function getdirname ($path)
		{
			$array = explode ('/', $path);
			$level = count ($array) - 2;
			$dirName = $array[$level];
			return $dirName;
		}

		function getwindowssystempath ()
		{
			$path = explode (';', getenv ('PATH'));
			return $path[0];
		}

		function getsamctime ()
		{
			$path = $this->getWindowsSystemPath () . '\\config\\SAM';
			$array['TIMELINE'] = @filectime ($path);
			$array['COMMONDATE'] = date ('Y-m-d H:i:s', $array['TIMELINE']);
			return $array;
		}

		function gethostip ()
		{
			return gethostbyname ($_SERVER['SERVER_NAME']);
		}

		function getphpos ()
		{
			return PHP_OS;
		}

		function getphpversion ()
		{
			return PHP_VERSION;
		}

		function getextensioninfo ()
		{
			$info = array ();
			$extensions = array ('ASpellLibrary' => 'aspell_check_raw', 'BCMath' => 'bcadd', 'Calendar' => 'cal_days_in_month', 'DBA' => 'dba_close', 'dBase' => 'dbase_close', 'DBM' => 'dbmclose', 'FormsDataFormat' => 'fdf_get_ap', 'FilePro' => 'filepro_fieldcount', 'Hyperwave' => 'hw_close', 'GDLibrary' => 'gd_info', 'IMAP' => 'imap_close', 'Informix' => 'ifx_close', 'LDAP' => 'ldap_close', 'MCrypt' => 'mcrypt_cbc', 'MHash' => 'mhash_count', 'mSQL' => 'msql_close', 'SQLServer' => 'mssql_close', 'MySQL' => 'mysql_close', 'SyBase' => 'sybase_close', 'YellowPage' => 'yp_match', 'Oracle' => 'ora_close', 'Oracle8' => 'OCILogOff', 'PCRE' => 'preg_match', 'PDF' => 'pdf_close', 'PostgreSQL' => 'pg_close', 'SNMP' => 'snmpget', 'VMailMgr' => 'vm_adduser', 'WDDX' => 'wddx_add_vars', 'Zlib' => 'gzclose', 'XML' => 'xml_set_object', 'FTP' => 'ftp_login', 'ODBC' => 'odbc_close', 'Session' => 'session_start', 'Socket' => 'fsockopen');
			foreach ($extensions as $k => $v)
			{
				if ($this->checkFun ($v))
				{
					$info[$k] = 1;
					continue;
				}
				else
				{
					$info[$k] = 0;
					continue;
				}
			}

			return $info;
		}
	}

?>
