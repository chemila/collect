<?

	class neat_xml
	{
		function parse_document ($xml)
		{
			$i = -1;
			$parser = xml_parser_create ();
			xml_parser_set_option ($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option ($parser, XML_OPTION_SKIP_WHITE, 0);
			xml_parse_into_struct ($parser, $xml, $vals);
			xml_parser_free ($parser);
			$this->xml_array = $this->_get_children ($vals, $i);
		}

		function _build_tag ($thisvals, $vals, &$i, $type)
		{
			$tag = array ();
			if (isset ($thisvals['attributes']))
			{
				$tag['ATTRIBUTES'] = $this->_decode_attribute ($thisvals['attributes']);
			}

			if ($type === 'complete')
			{
				$tag = $thisvals['value'];
			}
			else
			{
				$tag = array_merge ($tag, $this->_get_children ($vals, $i));
			}

			return $tag;
		}

		function _get_children ($vals, &$i)
		{
			$children = array ();
			while (++$i < count ($vals))
			{
				$type = $vals[$i]['type'];
				if (($type === 'complete' OR $type === 'open'))
				{
					$tag = $this->_build_tag ($vals[$i], $vals, $i, $type);
					if ($this->index_numeric)
					{
						$tag['TAG'] = $vals[$i]['tag'];
						$children[] = $tag;
						continue;
					}
					else
					{
						$key = $vals[$i]['tag'];
						$children[$key][] = $tag;
						continue;
					}

					continue;
				}
				else
				{
					if ($type === 'close')
					{
						break;
					}

					continue;
				}
			}

			foreach ($children as $key => $value)
			{
				if ((is_array ($value) AND count ($value) == 1))
				{
					$children[$key] = $value[0];
					continue;
				}
			}

			return $children;
		}

		function _decode_attribute ($t)
		{
			$t = str_replace ('&amp;', '&', $t);
			$t = str_replace ('&lt;', '<', $t);
			$t = str_replace ('&gt;', '>', $t);
			$t = str_replace ('&quot;', '"', $t);
			$t = str_replace ('&#039;', '\'', $t);
			return $t;
		}
	}

?>
