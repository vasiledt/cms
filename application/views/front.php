<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo cssUrl('main.css'); ?>" />
<?php
// add css files
if (!empty($extraCss)) {
	foreach ($extraCss as $cssFile) {
		$fileName = $cssFile;
		if (strpos($cssFile, '.css') === FALSE) {
			$fileName .= '.css';
		}
		echo '<link rel="stylesheet" type="text/css" href="'.$fileName.'" />';
	}
}
?>
<script type="text/javascript" src="<?php echo jsUrl('jquery/jquery-1.7.2.min.js'); ?>"></script>
<?php
// add js files
if (!empty($extraJs)) {
	foreach ($extraJs as $jsFile) {
		$fileName = $jsFile;
		if (strpos($jsFile, '.js') === FALSE) {
			$fileName .= '.js';
		}
		echo '<script type="text/javascript" src="'.$fileName.'"></script>';
	}
}
?>
<script type="text/javascript" src="<?php echo jsUrl('main.js'); ?>"></script>
</head>
<body>
	<div class="body">
		<?php 
		if (!isset($hideHeader)) {
			$this->load->view('header'); 
		}
		?>
		
		<div class="main">
			<?php
			if (!empty($viewToLoad)) {
				foreach ($viewToLoad as $id => $file) {
					if (!empty($$id)) { // view with data
						$this->load->view($file, $$id);
					} else { // view without data
						$this->load->view($file);
					}
				}
			}
			
			if (isset($data)) {
				echo $data;
			}
			?>
		</div>

		<?php 
		if (!isset($hideFooter)) {
			$this->load->view('footer'); 
		}
		?>
	</div>
</body>
</html>