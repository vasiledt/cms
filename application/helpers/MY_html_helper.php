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
			if (stream_resolve_include_path($path)) {
				return TRUE;
			}
		}
	}
	return FALSE;
}

function showObject($params = array()) {
	$output = '<div';
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
	
	$output .= '>';
	if (isset($params['html'])) {
		$output .= $params['html'];
	}
	$output .= '</div>';
	return $output;
}
?>