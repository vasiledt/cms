<?php
function cssUrl($fileName) {
	$_CI = & get_instance();
	return base_url(trim($_CI->config->item('cssUrl'), '/') . '/' . $fileName);
}

function jsUrl($fileName) {
	$_CI = & get_instance();
	return base_url(trim($_CI->config->item('jsUrl'), '/') . '/' . $fileName);
}

function imgUrl($fileName) {
	if (strpos($fileName, 'http://') !== FALSE) {
		return $fileName;
	}
	$_CI = & get_instance();
	return base_url(trim($_CI->config->item('imgUrl'), '/') . '/' . $fileName);
}

function resUrl($fileName) {
	if (strpos($fileName, 'http://') === 0) {
		return $fileName;
	}
	$_CI = & get_instance();
	return base_url(trim($_CI->config->item('resUrl'), '/') . '/' . $fileName);
}

function checkImg($fileName) {
	if (!empty($fileName)) {
		if (strpos($fileName, 'http://') !== FALSE) {
			$path = $fileName;
			if (getimagesize($path)) {
				return TRUE;
			}
		} else {
			$_CI = & get_instance();
			$path = trim($_CI->config->item('imgUrl'), '/') . '/' . $fileName;

			if (function_exists('stream_resolve_inlcude_path')) {
				if (stream_resolve_include_path($path)) {
					return TRUE;
				}
			} else {
				if (file_exists($path)) {
					return true;
				}
			}
		}
	}
	return FALSE;
}

function showObject($params = array()) {
	if (isset($params['ajax'])) {
		if (isset($params['html'])) {
			return $params['html'];
		} else {
			return '';
		}
	}
	$output = '<div';
	if (isset($params['id'])) {
		$output .= ' id="'.$params['id'].'"';
	}
	if (isset($params['class'])) {
		$output .= ' class="'.$params['class'].'"';
	}
	if (isset($params['style'])) {
		if (is_array($params['style'])) {
			$output .= ' style="';
			foreach ($params['style'] as $k => $v) {
				$output .= $k.': '.$v.'; ';
			}
			$output .= '" ';
		} elseif (is_string($params['style'])) {
			$output .= ' style="'.$params['style'].'"';
		}
	}
	if (isset($params['src'])) {
		$output .= ' src="'.$params['src'].'"';
	}
	
	$output .= '>';
	if (isset($params['html'])) {
		$output .= $params['html'];
	}
	$output .= '</div>';
	return $output;
}
?>