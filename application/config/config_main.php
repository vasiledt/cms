<?php
$config['cssUrl'] = 'resources/css/'; 
$config['jsUrl'] = 'resources/js/'; 
$config['imgUrl'] = 'images/'; 
$config['resUrl'] = 'resources/php/'; 


$config['status'] = array(
	0 => 'inactive',
	1 => 'active'
);

$config['obj_type'] = array(
	0 => 'image',
	1 => 'text'
);

$config['user_rights'] = array(
	0 => 'quest',
	1 => 'normal user',
	2 => 'admin'
);

$config['routes'] = array(
	'cmsobject' => array(
		'edit' => 'ajax/edit/cmsobject/',
		'save' => 'ajax/save/cmsobject/',
		'show' => 'ajax/show/cmsobject/'
	)
);
?>