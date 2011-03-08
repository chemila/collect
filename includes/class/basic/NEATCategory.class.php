<?

	class neat_category
	{
		var $NBS = null;
		var $NDB = null;
		var $NCA = null;
		var $table = null;
		var $tableFids = null;
		function setndb (&$NDB)
		{
			$this->NDB = &$NDB;
		}

		function setnbs (&$NBS)
		{
			$this->NBS = &$NBS;
		}

		function setnca (&$NCA)
		{
			$this->NCA = &$NCA;
		}

		function settable ($table)
		{
			$this->table = $table;
		}

		function setfield ($fields)
		{
			foreach ($fields as $k => $v)
			{
				$this->tableFids[$k] = $v;
			}

		}

		function gettree ($array, $pid, $deep = 0, $name)
		{
			static $getarr;
			++$deep;
			if ((is_array ($array) AND !empty ($array)))
			{
				foreach ($array as $key => $val)
				{
					if ($val[$this->tableFids['pid']] == $pid)
					{
						$i = $val[$this->tableFids['id']];
						foreach ($val as $k => $v)
						{
							$getarr[$name][$i][$k] = $v;
						}

						$getarr[$name][$i]['deep'] = $deep - 1;
						$this->getTree ($array, $val[$this->tableFids['id']], $deep, $name);
						continue;
					}
				}

				return $this->getarr[$name] = $getarr[$name];
			}
			else
			{
				return null;
			}

		}

		function getnav ($array, $id)
		{
			while ($array[$id][$this->tableFids['pid']] != NULL)
			{
				foreach ($array[$id] as $k => $v)
				{
					$getarr[$id][$k] = $v;
				}

				$id = $array[$id][$this->tableFids['pid']];
			}

			if (!empty ($getarr))
			{
				return array_reverse ($getarr);
			}
			else
			{
				return null;
			}

		}

		function changeorderid ($array, $id, $type)
		{
			$cateArray = $this->getChangeOrderID ($array, $id);
			($type == 1 ? $targetIndexTemp = $cateArray['info']['index'] - 1 : $targetIndexTemp = $cateArray['info']['index'] + 1);
			($cateArray['list'][$targetIndexTemp] ? $targetIndex = $targetIndexTemp : $targetIndex = $cateArray['info']['index']);
			$thisOrderID = $cateArray['list'][$targetIndex]['orderid'];
			$targetOrderID = $cateArray['info']['orderid'];
			if ($cateArray['list'][$targetIndex]['orderid'] == $cateArray['info']['orderid'])
			{
				($type == 1 ? $thisOrderID++ : $targetOrderID++);
			}

			$thisID = $id;
			$targetID = $cateArray['list'][$targetIndex]['id'];
			$thisCoData['id'] = $thisID;
			$thisUpData['orderid'] = $thisOrderID;
			$this->updateCategory ($thisUpData, $thisCoData);
			$targetCoData['id'] = $targetID;
			$targetUpData['orderid'] = $targetOrderID;
			$this->updateCategory ($targetUpData, $targetCoData);
		}

		function getchangeorderid ($array, $id)
		{
			foreach ($array as $k => $v)
			{
				(!$i[$v[$this->tableFids['pid']]] ? $i[$v[$this->tableFids['pid']]] = 1 : $i[$v[$this->tableFids['pid']]]++);
				$cateArrayTemp['list'][$v['pid']][$i[$v[$this->tableFids['pid']]]]['id'] = $v[$this->tableFids['id']];
				$cateArrayTemp['list'][$v['pid']][$i[$v[$this->tableFids['pid']]]]['orderid'] = $v[$this->tableFids['orderid']];
				if ($v[$this->tableFids['id']] == $id)
				{
					$cateArray['info']['index'] = $i[$v[$this->tableFids['pid']]];
					$cateArray['info']['id'] = $v[$this->tableFids['id']];
					$cateArray['info']['pid'] = $v[$this->tableFids['pid']];
					$cateArray['info']['orderid'] = $v[$this->tableFids['orderid']];
					continue;
				}
			}

			$cateArray['list'] = $cateArrayTemp['list'][$cateArray['info']['pid']];
			return $cateArray;
		}

		function getcategory ($id = '', $type = '')
		{
			$sql = 'SELECT * ';
			$sql .= 'FROM ' . $this->table . ' ';
			if (!$id)
			{
				$sql .= 'ORDER BY ' . $this->tableFids['orderid'] . ' DESC';
				$rs = $this->NDB->query ($sql);
				$i = 0;
				while ($rs->next_record ())
				{
					$array = $rs->getArray ();
					foreach ($array as $k => $v)
					{
						$cateArray[$i][$k] = $v;
					}

					++$i;
				}
			}
			else
			{
				(!$type ? $fids = $this->tableFids['id'] : $fids = $this->tableFids['pid']);
				$sql .= 'WHERE ' . $fids . ' = \'' . $id . '\'';
				$rs = $this->NDB->query ($sql);
				$rs->next_record ();
				$cateArray = $rs->getArray ();
			}

			return $cateArray;
		}

		function getunderside ($array, $id, $type = 1)
		{
			$pidArray[] = $id;
			if ($type == 1)
			{
				$getarr[0] = $array[$id][$this->tableFids['id']];
			}
			else
			{
				foreach ($array[$id] as $k => $v)
				{
					$getarr[0][$k] = $v;
				}
			}

			foreach ($array as $k => $v)
			{
				if (in_array ($v[$this->tableFids['pid']], $pidArray))
				{
					++$i;
					$pidArray[] = $v[$this->tableFids['id']];
					if ($type == 1)
					{
						$getarr[$i] = $v[$this->tableFids['id']];
						continue;
					}
					else
					{
						$getarr[$i] = $v;
						continue;
					}

					continue;
				}
			}

			return $getarr;
		}

		function addcategory ($categoryData)
		{
			$this->NBS->setTable ($this->table);
			$sql = $this->NBS->add ($categoryData);
			$this->NDB->update ($sql);
		}

		function delcategory ($array, $id)
		{
			$this->NBS->setTable ($this->table);
			$idArray = $this->getUnderside ($array, $id);
			$num = count ($idArray);
			foreach ($idArray as $k => $v)
			{
				++$i;
				$idArraySql .= $this->tableFids['id'] . ' = ' . $v;
				if ($i < $num)
				{
					$idArraySql .= ' OR ';
					continue;
				}
			}

			$sql = 'DELETE FROM ' . $this->table . ' ';
			$sql .= 'WHERE ' . $idArraySql;
			$this->NDB->update ($sql);
			return $idArray;
		}

		function updatecategory ($categoryData, $categoryCondition, $categoryConfig = '')
		{
			$array = $this->readCategoryCache ();
			$array = $this->getUnderside ($array, $categoryCondition[$this->tableFids['id']], $type = 1);
			unset ($array[0]);
			if (in_array ($categoryData[$this->tableFids['pid']], $array))
			{
				return false;
			}
			else
			{
				$this->NBS->setTable ($this->table);
				$sql = $this->NBS->update ($categoryData, $categoryCondition, $categoryConfig = '');
				$this->NDB->update ($sql);
				return true;
			}

		}

		function getnodemaxorderid ($pid)
		{
			$sql = 'SELECT MAX(' . $this->tableFids['orderid'] . ') + 1 AS ' . $this->tableFids['orderid'] . ' ';
			$sql .= 'FROM ' . $this->table . ' ';
			$sql .= 'WHERE ' . $this->tableFids['pid'] . ' = \'' . $pid . '\'';
			$rs = $this->NDB->query ($sql);
			$rs->next_record ();
			$rs->get ($this->tableFids['orderid']);
			return $rs->get ($this->tableFids['orderid']);
		}

		function debug ()
		{
			echo '<pre>';
			print_r ($this);
			echo '</pre>';
		}

		function docategorycache ($array)
		{
			$this->NCA->doCache ($array, 'categoryCache');
		}

		function readcategorycache ()
		{
			if (!$this->NCA->checkCacheFile ())
			{
				$catearray = $this->getCategory ();
				$getarray = $this->getTree ($catearray, 0, 0, 'category');
				$this->doCategoryCache ($getarray);
			}

			return $this->NCA->readCache ();
		}

		function getmixarray ($array)
		{
			$mixArray = array ();
			$ind = array ();
			foreach ($array as $v)
			{
				$v['array'] = array ();
				if ($v[$this->tableFids['pid']] == 0)
				{
					$i = count ($mixArray);
					$mixArray[$i] = $v;
					$ind[$v[$this->tableFids['id']]] = &$mixArray[$i];
					continue;
				}
				else
				{
					$i = count ($ind[$v[$this->tableFids['pid']]]['array']);
					$ind[$v['pid']]['array'][$i] = $v;
					$ind[$v[$this->tableFids['id']]] = &$ind[$v[$this->tableFids['pid']]]['array'][$i];
					continue;
				}
			}

			return $mixArray;
		}
	}

?>
