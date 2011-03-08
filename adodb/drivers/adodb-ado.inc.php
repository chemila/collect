<?


	if (!defined ('ADODB_DIR'))
	{
		exit ();
	}

	define ('_ADODB_ADO_LAYER', 1);
	class adodb_ado extends adoconnection
	{
		var $databaseType = 'ado';
		var $_bindInputArray = false;
		var $fmtDate = '\'Y-m-d\'';
		var $fmtTimeStamp = '\'Y-m-d, h:i:sA\'';
		var $replaceQuote = '\'\'';
		var $dataProvider = 'ado';
		var $hasAffectedRows = true;
		var $adoParameterType = 201;
		var $_affectedRows = false;
		var $_thisTransactions = null;
		var $_cursor_type = 3;
		var $_cursor_location = 3;
		var $_lock_type = -1;
		var $_execute_option = -1;
		var $poorAffectedRows = true;
		var $charPage = null;
		function adodb_ado ()
		{
			$this->_affectedRows = new VARIANT ();
		}

		function serverinfo ()
		{
			if (!empty ($this->_connectionID))
			{
				$desc = $this->_connectionID->provider;
			}

			return array ('description' => $desc, 'version' => '');
		}

		function _affectedrows ()
		{
			if (5 <= PHP_VERSION)
			{
				return $this->_affectedRows;
			}

			return $this->_affectedRows->value;
		}

		function _connect ($argHostname, $argUsername, $argPassword, $argProvider = 'MSDASQL')
		{
			$u = 'UID';
			$p = 'PWD';
			if (!empty ($this->charPage))
			{
				$dbc = new COM ('ADODB.Connection', null, $this->charPage);
			}
			else
			{
				$dbc = new COM ('ADODB.Connection');
			}

			if (!$dbc)
			{
				return false;
			}

			if ($argProvider == 'mssql')
			{
				$u = 'User Id';
				$p = 'Password';
				$argProvider = 'SQLOLEDB';
				if (!$argUsername)
				{
					$argHostname .= ';Trusted_Connection=Yes';
				}
			}
			else
			{
				if ($argProvider == 'access')
				{
					$argProvider = 'Microsoft.Jet.OLEDB.4.0';
				}
			}

			if ($argProvider)
			{
				$dbc->Provider = $argProvider;
			}

			if ($argUsername)
			{
				$argHostname .= '' . ';' . $u . '=' . $argUsername;
			}

			if ($argPassword)
			{
				$argHostname .= '' . ';' . $p . '=' . $argPassword;
			}

			if ($this->debug)
			{
				adoconnection::outp ('Host=' . $argHostname . ('' . '<BR>
 version=' . $dbc->version));
			}

			@$dbc->Open ((string)$argHostname);
			$this->_connectionID = $dbc;
			$dbc->CursorLocation = $this->_cursor_location;
			return 0 < $dbc->State;
		}

		function _pconnect ($argHostname, $argUsername, $argPassword, $argProvider = 'MSDASQL')
		{
			return $this->_connect ($argHostname, $argUsername, $argPassword, $argProvider);
		}

		function metatables ()
		{
			$arr = array ();
			$dbc = $this->_connectionID;
			$adors = @$dbc->OpenSchema (20);
			if ($adors)
			{
				$f = $adors->Fields (2);
				$t = $adors->Fields (3);
				while (!$adors->EOF)
				{
					$tt = substr ($t->value, 0, 6);
					if ((($tt != 'SYSTEM' AND $tt != 'ACCESS') AND $t->value == 'TABLE'))
					{
						$arr[] = $f->value;
					}

					$adors->MoveNext ();
				}

				$adors->Close ();
			}

			return $arr;
		}

		function metacolumns ($table)
		{
			$table = strtoupper ($table);
			$arr = array ();
			$dbc = $this->_connectionID;
			$adors = @$dbc->OpenSchema (4);
			if ($adors)
			{
				$t = $adors->Fields (2);
				while (!$adors->EOF)
				{
					if (strtoupper ($t->Value) == $table)
					{
						$fld = new ADOFieldObject ();
						$c = $adors->Fields (3);
						$fld->name = $c->Value;
						$fld->type = 'CHAR';
						$fld->max_length = -1;
						$arr[strtoupper ($fld->name)] = $fld;
					}

					$adors->MoveNext ();
				}

				$adors->Close ();
			}

			$false = false;
			return (empty ($arr) ? $false : $arr);
		}

		function _query ($sql, $inputarr = false)
		{
			$dbc = $this->_connectionID;
			$false = false;
			if ($inputarr)
			{
				if (!empty ($this->charPage))
				{
					$oCmd = new COM ('ADODB.Command', null, $this->charPage);
				}
				else
				{
					$oCmd = new COM ('ADODB.Command');
				}

				$oCmd->ActiveConnection = $dbc;
				$oCmd->CommandText = $sql;
				$oCmd->CommandType = 1;
				foreach ($inputarr as $val)
				{
					$this->adoParameterType = 130;
					$p = $oCmd->CreateParameter ('name', $this->adoParameterType, 1, strlen ($val), $val);
					$oCmd->Parameters->Append ($p);
				}

				$p = false;
				$rs = $oCmd->Execute ();
				$e = $dbc->Errors;
				if (0 < $dbc->Errors->Count)
				{
					return $false;
				}

				return $rs;
			}

			$rs = @$dbc->Execute ($sql, $this->_affectedRows, $this->_execute_option);
			if (0 < $dbc->Errors->Count)
			{
				return $false;
			}

			if (!$rs)
			{
				return $false;
			}

			if ($rs->State == 0)
			{
				$true = true;
				return $true;
			}

			return $rs;
		}

		function begintrans ()
		{
			if ($this->transOff)
			{
				return true;
			}

			if (isset ($this->_thisTransactions))
			{
				if (!$this->_thisTransactions)
				{
					return false;
				}
				else
				{
					$o = $this->_connectionID->Properties ('Transaction DDL');
					$this->_thisTransactions = ($o ? true : false);
					if (!$o)
					{
						return false;
					}
				}
			}

			@$this->_connectionID->BeginTrans ();
			$this->transCnt += 1;
			return true;
		}

		function committrans ($ok = true)
		{
			if (!$ok)
			{
				return $this->RollbackTrans ();
			}

			if ($this->transOff)
			{
				return true;
			}

			@$this->_connectionID->CommitTrans ();
			if ($this->transCnt)
			{
				$this->transCnt -= 1;
			}

			return true;
		}

		function rollbacktrans ()
		{
			if ($this->transOff)
			{
				return true;
			}

			@$this->_connectionID->RollbackTrans ();
			if ($this->transCnt)
			{
				$this->transCnt -= 1;
			}

			return true;
		}

		function errormsg ()
		{
			$errc = $this->_connectionID->Errors;
			if ($errc->Count == 0)
			{
				return '';
			}

			$err = $errc->Item ($errc->Count - 1);
			return $err->Description;
		}

		function errorno ()
		{
			$errc = $this->_connectionID->Errors;
			if ($errc->Count == 0)
			{
				return 0;
			}

			$err = $errc->Item ($errc->Count - 1);
			return $err->NativeError;
		}

		function _close ()
		{
			if ($this->_connectionID)
			{
				$this->_connectionID->Close ();
			}

			$this->_connectionID = false;
			return true;
		}
	}

	class adorecordset_ado extends adorecordset
	{
		var $bind = false;
		var $databaseType = 'ado';
		var $dataProvider = 'ado';
		var $_tarr = false;
		var $_flds = null;
		var $canSeek = true;
		var $hideErrors = true;
		function adorecordset_ado ($id, $mode = false)
		{
			if ($mode === false)
			{
				global $ADODB_FETCH_MODE;
				$mode = $ADODB_FETCH_MODE;
			}

			$this->fetchMode = $mode;
			return $this->ADORecordSet ($id, $mode);
		}

		function fetchfield ($fieldOffset = -1)
		{
			$off = $fieldOffset + 1;
			$o = new ADOFieldObject ();
			$rs = $this->_queryID;
			$f = $rs->Fields ($fieldOffset);
			$o->name = $f->Name;
			$t = $f->Type;
			$o->type = $this->MetaType ($t);
			$o->max_length = $f->DefinedSize;
			$o->ado_type = $t;
			return $o;
		}

		function fields ($colname)
		{
			if ($this->fetchMode & ADODB_FETCH_ASSOC)
			{
				return $this->fields[$colname];
			}

			if (!$this->bind)
			{
				$this->bind = array ();
				for ($i = 0; $i < $this->_numOfFields; ++$i)
				{
					$o = $this->FetchField ($i);
					$this->bind[strtoupper ($o->name)] = $i;
				}
			}

			return $this->fields[$this->bind[strtoupper ($colname)]];
		}

		function _initrs ()
		{
			$rs = $this->_queryID;
			$this->_numOfRows = $rs->RecordCount;
			$f = $rs->Fields;
			$this->_numOfFields = $f->Count;
		}

		function _seek ($row)
		{
			$rs = $this->_queryID;
			if ($row < $this->_currentRow)
			{
				return false;
			}

			@$rs->Move ((int)$row - $this->_currentRow - 1);
			return true;
		}

		function metatype ($t, $len = -1, $fieldobj = false)
		{
			if (is_object ($t))
			{
				$fieldobj = $t;
				$t = $fieldobj->type;
				$len = $fieldobj->max_length;
			}

			if (!is_numeric ($t))
			{
				return $t;
			}

			switch ($t)
			{
				case 0:
				{
				}

				case 12:
				{
				}

				case 8:
				{
				}

				case 129:
				{
				}

				case 130:
				{
				}

				case 200:
				{
				}

				case 202:
				{
				}

				case 128:
				{
				}

				case 204:
				{
				}

				case 72:
				{
					if ($len <= $this->blobSize)
					{
						return 'C';
					}
				}

				case 201:
				{
				}

				case 203:
				{
					return 'X';
				}

				case 128:
				{
				}

				case 204:
				{
				}

				case 205:
				{
					return 'B';
				}

				case 7:
				{
				}

				case 133:
				{
					return 'D';
				}

				case 134:
				{
				}

				case 135:
				{
					return 'T';
				}

				case 11:
				{
					return 'L';
				}

				case 16:
				{
				}

				case 2:
				{
				}

				case 3:
				{
				}

				case 4:
				{
				}

				case 17:
				{
				}

				case 18:
				{
				}

				case 19:
				{
				}

				case 20:
				{
					return 'I';
				}

				default:
				{
					return 'N';
				}
			}

		}

		function _fetch ()
		{
			$rs = $this->_queryID;
			if ((!$rs OR $rs->EOF))
			{
				$this->fields = false;
				return false;
			}

			$this->fields = array ();
			if (!$this->_tarr)
			{
				$tarr = array ();
				$flds = array ();
				$i = 0;
				for ($max = $this->_numOfFields; $i < $max; ++$i)
				{
					$f = $rs->Fields ($i);
					$flds[] = $f;
					$tarr[] = $f->Type;
				}

				$this->_tarr = $tarr;
				$this->_flds = $flds;
			}

			$t = reset ($this->_tarr);
			$f = reset ($this->_flds);
			if ($this->hideErrors)
			{
				$olde = error_reporting (E_ERROR | E_CORE_ERROR);
			}

			$i = 0;
			for ($max = $this->_numOfFields; $i < $max; ++$i)
			{
				switch ($t)
				{
					case 135:
					{
						if (!strlen ((string)$f->value))
						{
							$this->fields[] = false;
							break;
						}
						else
						{
							if (!is_numeric ($f->value))
							{
								$val = variant_date_to_timestamp ($f->value);
							}
							else
							{
								$val = $f->value;
							}

							$this->fields[] = adodb_date ('Y-m-d H:i:s', $val);
							break;
						}

						break;
					}

					case 133:
					{
						if ($val = $f->value)
						{
							$this->fields[] = substr ($val, 0, 4) . '-' . substr ($val, 4, 2) . '-' . substr ($val, 6, 2);
							break;
						}
						else
						{
							$this->fields[] = false;
							break;
						}

						break;
					}

					case 7:
					{
						if (!strlen ((string)$f->value))
						{
							$this->fields[] = false;
							break;
						}
						else
						{
							if (!is_numeric ($f->value))
							{
								$val = variant_date_to_timestamp ($f->value);
							}
							else
							{
								$val = $f->value;
							}

							if ($val % 86400 == 0)
							{
								$this->fields[] = adodb_date ('Y-m-d', $val);
								break;
							}
							else
							{
								$this->fields[] = adodb_date ('Y-m-d H:i:s', $val);
								break;
							}

							break;
						}

						break;
					}

					case 1:
					{
						$this->fields[] = false;
						break;
					}

					case 6:
					{
						adoconnection::outp ('<b>' . $f->Name . ': currency type not supported by PHP</b>');
						$this->fields[] = (double)$f->value;
						break;
					}

					default:
					{
						$this->fields[] = $f->value;
						break;
					}
				}

				$f = next ($this->_flds);
				$t = next ($this->_tarr);
			}

			if ($this->hideErrors)
			{
				error_reporting ($olde);
			}

			@$rs->MoveNext ();
			if ($this->fetchMode & ADODB_FETCH_ASSOC)
			{
				$this->fields = &$this->GetRowAssoc (ADODB_ASSOC_CASE);
			}

			return true;
		}

		function nextrecordset ()
		{
			$rs = $this->_queryID;
			$this->_queryID = $rs->NextRecordSet ();
			if ($this->_queryID == null)
			{
				return false;
			}

			$this->_currentRow = -1;
			$this->_currentPage = -1;
			$this->bind = false;
			$this->fields = false;
			$this->_flds = false;
			$this->_tarr = false;
			$this->_inited = false;
			$this->Init ();
			return true;
		}

		function _close ()
		{
			$this->_flds = false;
			@$this->_queryID->Close ();
			$this->_queryID = false;
		}
	}

?>
