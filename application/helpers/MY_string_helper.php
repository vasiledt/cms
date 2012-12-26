<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

// extract a portion from a source string delimitated by $start and $end strings
// $start and $end are not included into result!
if ( ! function_exists('substring') ) {
	function substring($source, $start = '', $end = '', &$offset = 0) {
		$pos1 = 0;
		if (!empty($start)) {
			$pos1 = strpos($source, $start, $offset);
			if ($pos1 !== FALSE) { // $start found, exclude him from result
				$pos1 = $pos1 + strlen($start);
				$offset = $pos1;
			} else { // no $start delimitator found
				return '';
			}
			
		}
		$pos2 = strlen($source);
		if (!empty($end)) {
			$pos2 = strpos($source, $end, $offset);
			if ($pos2 !== FALSE) { // $end found, increment $offset
				$offset = $pos2 + strlen($end);
			}
		}
		return substr($source, $pos1, $pos2 - $pos1);
	}
}

// process <item></item> elements from a source string 
if ( ! function_exists('processItems') ) {
	function processItems($source) {
		$_CI = & get_instance();
		$count = 0;
		// <item type="libraryName" class="" id="" style="" params="" />
		while ($val = substring($source, '{item ', ' /}')) {
			$matches = array();
			$pattern = '/\s?([^=]*)=[\'|"]([^\'"]*)[\'|"]/i';
			preg_match_all($pattern, $val, $matches);
			if (!empty($matches[1]) && !empty($matches[2]) && (sizeof($matches[1]) == sizeof($matches[2]))) {
				$params = array();
				foreach ($matches[1] as $i => $pname) {
					$params[$pname] = $matches[2][$i];
				}
				// echo '<pre>'.print_r($params, TRUE).'</pre>';
				if (isset($params['type']) && isset($params['id'])) {
					$library = strtolower($params['type']);
					unset($params['type']);
					$idItem = $params['id'];
					$_CI->load->library($library);
					if (isset($_CI->$library)) {
						if (is_numeric($idItem)) {
							$_CI->$library->load($idItem);
						} else {
							$_CI->$library->loadByTitle($idItem);
						}
						$source = str_replace('{item '.$val.' /}', $_CI->$library->show($params), $source);
					}
				}
			}
			$count++;
			if ($count > 10)
				break;
		}
		return $source;
	}
}

if ( !function_exists('parseStyle') ) {
	function parseStyle($source) {
		$result = $source;
		
		$pattern = '/\s?([^:]*):\s?([^;]*);?/i';
		$params = array();
		preg_match_all($pattern, $source, $params);
		if (!empty($params[1])) {
			$result = array();
			foreach ($params[1] as $i => $sname) {
				$result[$sname] = $params[2][$i];
			}
		}
		
		return $result;
	}
}


/* End of file MY_string_helper.php */
/* Location: ./application/helpers/MY_string_helper.php */