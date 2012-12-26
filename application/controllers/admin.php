<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('a_controller.php');

class Admin extends a_controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html', 'array'));
	}

	public function index() {
		// parent::index(); return;
		$this->data['extraCss'] = array(
			cssUrl('jquery/jquery-ui-1.8.21.custom.css')
		);
		$this->data['extraJs'] = array(
			jsUrl('jquery/jquery-ui-1.8.21.custom.min.js')
		);
		// $this->load->library('CMSObject');
		// $this->cmsobject->loadByTitle('testCMSObject');
		// $this->cmsobject->load(1);
		
		// $params = array(
			// 'class' => 'cmsObject'
		// );
		// $this->data['data'] = $this->cmsobject->show($params);
		$this->data['data'] = processItems('{item type="CMSObject" id="testPage" /}');
		$this->load->view('front', $this->data);
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */