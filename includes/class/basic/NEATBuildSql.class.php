<?
	class neatbulidsql
	{
		var $debugMOD = false;
		var $table = null;
		function neatbulidsql ($table)
		{
			$this->setTable ($table);
			if ($this->debugMOD)
			{
				$this->showHeader ();
			}

		}

		function settable ($table)
		{
			$this->table = $table;
		}

		function checkdata ($data, $config = '')
		{
			if (!is_array ($data))
			{
				exit ('这里可以用公用的出错函数');
			}

			$tempSql['fidNum'] = count ($data);
			foreach ($data as $fid => $var)
			{
				++$i;
				$var = '\'' . $var . '\'';
				$tempSql['Fids'] .= $fid;
				$tempSql['Var'] .= $var;
				$tempSql['sqlCondition'] .= $fid . ' = ' . $var;
				if ($config[$fid])
				{
					if (!$config[$fid]['method'])
					{
						exit ($fid . '\'s method not found!');
					}

					if (!$config[$fid]['num'])
					{
						exit ($fid . '\'s num not found!');
					}

					$var = $fid . ' ' . $config[$fid]['method'] . ' ' . $config[$fid]['num'];
				}

				$tempSql['updateFids'] .= $fid . ' = ' . $var;
				if ($i < $tempSql['fidNum'])
				{
					$tempSql['sqlCondition'] .= ' AND ';
					$tempSql['Fids'] .= ', ';
					$tempSql['Var'] .= ', ';
					$tempSql['updateFids'] .= ', ';
					continue;
				}
			}

			return $tempSql;
		}

		function add ($data)
		{
			$insert = $this->checkData ($data);
			$sql = 'INSERT INTO ' . $this->table . ' ';
			$sql .= '(' . $insert['Fids'] . ') ';
			$sql .= 'VALUES (' . $insert['Var'] . ')';
			if ($this->debugMOD)
			{
				$debugData['data'] = $data;
				$this->debug (1, $debugData, $sql);
			}

			return $sql;
		}

		function del ($condition)
		{
			$del = $this->checkData ($condition);
			$sql = 'DELETE FROM ' . $this->table . ' ';
			$sql .= 'WHERE ' . $del['sqlCondition'];
			if ($this->debugMOD)
			{
				$debugData['condition'] = $condition;
				$this->debug (2, $debugData, $sql);
			}

			return $sql;
		}

		function update ($data, $condition, $config = '')
		{
			$d = $this->checkData ($data, $config);
			$c = $this->checkData ($condition);
			$sqlCondition = ' WHERE ' . $c['sqlCondition'];
			$sql = 'UPDATE ' . $this->table . ' ';
			$sql .= 'SET ';
			$sql .= $d['updateFids'];
			if (0 < $c['fidNum'])
			{
				$sql .= $sqlCondition;
			}

			if ($this->debugMOD)
			{
				$debugData['data'] = $data;
				$debugData['condition'] = $condition;
				$debugData['config'] = $config;
				$this->debug (3, $debugData, $sql);
			}

			return $sql;
		}

		function debug ($type, $data, $sql)
		{
			$typeList[1] = 'add';
			$typeList[2] = 'del';
			$typeList[3] = 'update';
			echo 'ID : ' . $this->autoID . '<br><br>';
			echo '<font size="2" color="green">调用函数 : $this->' . $typeList[$type] . '()</font><br><br>';
			echo '<font size="2" color="blue">1. SQL语句 : </font><br><br><textarea rows=4 cols=120 id=\'sql' . $this->autoID . '\'>' . $sql . '</textarea><br><br><input type=button value=\'复制代码\' onclick="copyCode(sql' . $this->autoID . ')"><br><br>
';
			echo '<font size="2" color="red">2. 传入参数 : </font><br><br>';
			echo '<pre>';
			echo '$data = ';
			($data['data'] ? print_r ($data['data']) : print 'NULL');
			echo '</pre>';
			echo '<pre>';
			echo '$condition = ';
			($data['condition'] ? print_r ($data['condition']) : print 'NULL');
			echo '</pre>';
			echo '<pre>';
			echo '$config = ';
			($data['config'] ? print_r ($data['config']) : print 'NULL');
			echo '</pre>';
			echo '<hr size=1>';
			++$this->autoID;
		}

		function showheader ()
		{
			echo '<script>
';
			echo 'function copyCode(obj) {
';
			echo 'var rng = document.body.createTextRange();
';
			echo 'rng.moveToElementText(obj);
';
			echo 'rng.scrollIntoView();
';
			echo 'rng.select();
';
			echo 'rng.execCommand("Copy");
';
			echo 'rng.collapse(false);
';
			echo '};
';
			echo '</script>
';
			echo '<style type=\'text/css\'>
';
			echo 'BODY {font: 12px Verdana;}
';
			echo 'input, textarea {
';
			echo 'font-family: Verdana;
';
			echo 'font-size: 8pt;
';
			echo 'border: 1px solid #C0C0C0;
';
			echo 'color:#333333; background-color:#EFEFEF
';
			echo '}
';
			echo '</style>
';
			echo '<center><font size="4" color="red">NEATBuildSql Class DEBUG Mode</font><br><br>Powered by NEATSTUDIO</center><br>';
		}
	}

?>
